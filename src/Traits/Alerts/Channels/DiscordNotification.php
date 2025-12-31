<?php

namespace Beartropy\AlertSystem\Traits\Alerts\Channels;

use Beartropy\AlertSystem\Services\DiscordService;

/**
 * Trait for sending Discord notifications.
 */
trait DiscordNotification
{
    /**
     * Send an alert notification via Discord.
     *
     * @param \Beartropy\AlertSystem\Models\AlertRecipient|object $recipient The recipient object containing bot config
     * @param string $type The alert type
     * @param string $message The alert message
     * @param array<string, mixed> $details Additional details to include in the message
     * @param string|null $subject Optional subject (used in message formatting)
     * @return void
     */
    public function discordAlert($recipient, $type, $message, $details = [], $subject = null)
    {

        $discord = app(DiscordService::class);

        if (!empty($details)) {
            $fields = array_map(
                fn($key, $value) => "**{$key}**: {$value}",
                array_keys($details),
                $details
            );
            $message .= "\n\n" . implode("\n", $fields);
        }

        $botKey = $recipient->bot ?? 'default';
        $discord->sendMessage($message, $botKey);
    }
}
