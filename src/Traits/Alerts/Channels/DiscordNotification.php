<?php

namespace Beartropy\AlertSystem\Traits\Alerts\Channels;

use Beartropy\AlertSystem\Services\DiscordService;

trait DiscordNotification
{
    public function discordAlert($recipient, $type, $message, $details = [], $subject = null) {

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
