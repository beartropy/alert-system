<?php

namespace Beartropy\AlertSystem\Services;

use GuzzleHttp\Client;

class DiscordService
{
    protected Client $client;

    public function __construct() {}

    public function sendMessage(string $content, string $botKey = 'default'): void
    {
        $botConfig = config("alert-system.discord.bots.$botKey", config('alert-system.discord.bots.default'));

        $webhookUrl = $botConfig['webhook'] ?? null;
        $proxy = $botConfig['proxy'] ?? null;
        $verify = $botConfig['verify'] ?? true;

        $client = new \GuzzleHttp\Client([
            'verify' => $verify,
            'proxy' => $proxy,
        ]);

        $client->post($webhookUrl, [
            'json' => [
                'content' => $content,
            ],
        ]);
    }

}
