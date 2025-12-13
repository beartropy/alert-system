<?php

namespace Beartropy\AlertSystem\Services;

use GuzzleHttp\Client;

class TelegramService
{
    protected Client $client;

    public function __construct() {}

    public function sendMessage(string $chatId, string $message, string $botKey = 'default'): void
    {
        $botConfig = config("alert-system.telegram.bots.$botKey", config('alert-system.telegram.bots.default'));

        $token = $botConfig['token'];
        $proxy = $botConfig['proxy'] ?? null;
        $verify = $botConfig['verify'] ?? true;

        $client = new \GuzzleHttp\Client([
            'base_uri' => "https://api.telegram.org/bot{$token}/",
            'verify' => $verify,
            'proxy' => $proxy,
        ]);

        $client->post('sendMessage', [
            'json' => [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ],
        ]);
    }

}
