<?php

namespace Beartropy\AlertSystem;

use Beartropy\AlertSystem\Models\AlertLog;
use Beartropy\AlertSystem\Models\AlertRecipient;
use Beartropy\AlertSystem\Traits\Alerts\AllChannelsTraits;
use Illuminate\Support\Facades\Log;

class AlertService
{
    use AllChannelsTraits;

    /**
     * Send an alert to configured recipients based on type.
     *
     * This method retrieves active recipients for the given alert type, checks for cooldowns,
     * and dispatches alerts via supported channels (Mail, Telegram, Discord).
     * It also handles logging and database recording of the alert attempt.
     *
     * @param string $type The type of alert (e.g., 'system_error', 'user_signup')
     * @param string $message The main message body of the alert
     * @param array<string, mixed> $details Additional context or data for the alert
     * @param array<string, mixed> $options Custom options (e.g., 'mailSubject', 'cooldown')
     * @return void
     *
     * @throws \InvalidArgumentException If an unsupported channel is encountered
     * @throws \Throwable If sending fails
     */
    public function send(string $type, string $message, array $details = [], array $options = []): void
    {
        if (!$this->isEnvironmentAllowed()) {
            return;
        }

        $recipients = AlertRecipient::with('type', 'channel')
            ->whereHas('type', fn ($q) => $q->where('name', $type))
            ->get();

        $logRecipients = $recipients->map(fn ($r) => [
            'channel' => $r->channel->name,
            'address' => $r->address,
        ])->all();

        $subject = $options['mailSubject'] ?? "{$type} Alert";
        $cooldown = $options['cooldown'] ?? config('alert-system.cooldown_minutes', 10);

        foreach ($recipients as $recipient) {
            if (!$recipient->is_active) {
                continue;
            }

            if ($this->isInCooldown($type, $recipient->address, $recipient->channel->name, $message, $cooldown)) {
                $this->logCooldownSkip($recipient, $type, $message, $details, $subject, $logRecipients, $cooldown);
                continue;
            }

            try {
                $this->sendAlertViaChannel($recipient, $type, $message, $details, $subject);

                $this->handleLog(true, compact(
                    'type', 'recipient', 'subject', 'message', 'details', 'logRecipients'
                ));

                $this->handleDB(true, compact(
                    'type', 'recipient', 'subject', 'message', 'details'
                ));
            } catch (\Throwable $e) {
                $error = $e->getMessage();

                $this->handleLog(false, compact(
                    'type', 'recipient', 'subject', 'message', 'details', 'logRecipients', 'error'
                ));

                $this->handleDB(false, compact(
                    'type', 'recipient', 'subject', 'message', 'details', 'error'
                ));
            }
        }
    }

    /**
     * Check if the current environment is allowed to send alerts.
     *
     * @return bool True if the current environment is in the allowed list
     */
    protected function isEnvironmentAllowed(): bool
    {
        return in_array(app()->environment(), config('alert-system.envs', []));
    }

    /**
     * Check if an alert is currently in cooldown for a specific recipient.
     *
     * @param string $type Alert type
     * @param string $address Recipient address
     * @param string $channel Channel name
     * @param string $message Alert message
     * @param int $cooldown Cooldown duration in minutes
     * @return bool True if a successfully sent alert matching criteria exists within the cooldown period
     */
    protected function isInCooldown(string $type, string $address, string $channel, string $message, int $cooldown): bool
    {
        if ($cooldown <= 0) {
            return false;
        }

        return AlertLog::where('type', $type)
            ->where('address',$address)
            ->where('channel', $channel)
            ->where('message', $message)
            ->where('status', 'success')
            ->where('sent_at', '>=', now()->subMinutes($cooldown))
            ->exists();
    }

    /**
     * Log that an alert was skipped due to cooldown.
     *
     * @param AlertRecipient $recipient The recipient object
     * @param string $type Alert type
     * @param string $message Alert message
     * @param array<string, mixed> $details Alert details
     * @param string $subject Alert subject
     * @param array<int, array<string, string>> $logRecipients List of all recipients for logging context
     * @param int $cooldown Cooldown minutes
     * @return void
     */
    protected function logCooldownSkip($recipient, $type, $message, $details, $subject, $logRecipients, $cooldown): void
    {
        Log::channel(config('alert-system.logging.channel', 'stack'))
            ->{config('alert-system.logging.level', 'info')}("[AlertSystem] Skipping alert due to cooldown", [
                'cooldown_minutes' => $cooldown,
                'type' => $type,
                'channel' => $recipient->channel->name,
                'address' => $recipient->address,
                'bot' => $recipient->bot ?? null,
                'subject' => $subject,
                'message' => $message,
                'details' => $details,
                'recipients' => $logRecipients,
            ]);
    }

    /**
     * Dispatch the alert to the specific channel.
     *
     * @param AlertRecipient $recipient
     * @param string $type
     * @param string $message
     * @param array<string, mixed> $details
     * @param string $subject
     * @return void
     *
     * @throws \InvalidArgumentException If channel is not supported
     */
    protected function sendAlertViaChannel($recipient, string $type, string $message, array $details, string $subject): void
    {
        $details = $this->flattenAlertDetails($details);
        match ($recipient->channel->name) {
            'mail'     => $this->mailAlert($recipient, $type, $message, $details, $subject),
            'telegram' => $this->telegramAlert($recipient, $type, $message, $details, $subject),
            'discord'  => $this->discordAlert($recipient, $type, $message, $details, $subject),
            default    => throw new \InvalidArgumentException("Unsupported channel: {$recipient->channel->name}")
        };
    }

    /**
     * Record the alert attempt in the database.
     *
     * @param bool $success Whether the alert was sent successfully
     * @param array<string, mixed> $info Information about the alert (recipient, type, details, etc.)
     * @return void
     */
    public function handleDB(bool $success, array $info): void
    {
        if (!config('alert-system.db-history', false)) {
            return;
        }

        $recipient = $info['recipient'];
        AlertLog::create([
            'type'          => $info['type'] ?? 'unknown',
            'channel'       => $recipient->channel->name,
            'address'       => $recipient->address,
            'bot'           => $recipient->bot ?? null,
            'status'        => $success ? 'success' : 'failure',
            'subject'       => $info['subject'] ?? 'Alert',
            'message'       => $info['message'] ?? 'No message provided',
            'details'       => $info['details'] ?? [],
            'error_message' => $info['error'] ?? null,
            'sent_at'       => now(),
        ]);
    }

    /**
     * Log the alert attempt result.
     *
     * @param bool $success Whether the alert was sent successfully
     * @param array<string, mixed> $info Information about the alert
     * @return void
     */
    public function handleLog(bool $success, array $info): void
    {
        if (!config('alert-system.logging.enabled', true)) {
            return;
        }

        $recipient = $info['recipient'];
        $logLevel = $success
            ? config('alert-system.logging.level', 'info')
            : 'error';

        Log::channel(config('alert-system.logging.channel', 'stack'))
            ->{$logLevel}('[AlertSystem] ' . ($success ? 'Alert successfully sent' : 'Failed to send alert'), [
                'type'      => $info['type'] ?? 'unknown',
                'channel'   => $recipient->channel->name,
                'address'   => $recipient->address,
                'bot'       => $recipient->bot ?? null,
                'subject'   => $info['subject'] ?? 'Alert',
                'message'   => $info['message'] ?? 'No message provided',
                'details'   => $info['details'] ?? [],
                'recipients'=> $info['logRecipients'] ?? [],
                'error'     => $info['error'] ?? null,
            ]);
    }
}
