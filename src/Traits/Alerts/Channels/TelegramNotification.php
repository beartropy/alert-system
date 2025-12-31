<?php

namespace Beartropy\AlertSystem\Traits\Alerts\Channels;

use Beartropy\AlertSystem\Services\TelegramService;
use Illuminate\Support\Facades\Log;
use Beartropy\AlertSystem\Facades\Alert;
use Beartropy\AlertSystem\Models\AlertRecipient;

/**
 * Trait for sending Telegram notifications.
 */
trait TelegramNotification
{
    /**
     * Send an alert notification via Telegram.
     *
     * @param \Beartropy\AlertSystem\Models\AlertRecipient|object $recipient The recipient object containing address (chat ID) and bot config
     * @param string $type The alert type
     * @param string $message The alert message
     * @param array<string, mixed> $details Additional details to format in the message
     * @param string|null $subject Optional subject to bold at the top
     * @return void
     */
    public function telegramAlert($recipient, $type, $message, $details = [], $subject = null)
    {
        $telegram = app(TelegramService::class);

        $text = "<b>" . ($subject) . "</b>\n" . $message . "\n\n";

        foreach ($details as $k => $v) {
            $text .= "<b>{$k}:</b> {$v}\n";
        }

        $botKey = $recipient->bot ?? 'default';
        $telegram->sendMessage($recipient->address, $text, $botKey);
    }
}
