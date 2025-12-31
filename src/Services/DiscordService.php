<?php

namespace Beartropy\AlertSystem\Services;

use GuzzleHttp\Client;

/**
 * Service for sending alerts via Discord.
 *
 * This service handles the dispatching of messages to Discord webhooks,
 * supporting proxy and verification configurations.
 */
class DiscordService
{
    /**
     * @var \GuzzleHttp\Client The HTTP client instance (though created per request in valid method currently, defined property suggests intention).
     */
    protected Client $client;

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Send a message to a Discord channel via webhook.
     *
     * @param string $content The message content
     * @param string $botKey The configuration key for the bot/webhook (defaults to 'default')
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException If the HTTP request fails
     */
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
