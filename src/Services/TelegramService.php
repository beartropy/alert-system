<?php

namespace Beartropy\AlertSystem\Services;

use GuzzleHttp\Client;

/**
 * Service for sending alerts via Telegram.
 *
 * This service handles the dispatching of messages to Telegram chats using simple bot API,
 * supporting proxy and verification configurations.
 */
class TelegramService
{
    /**
     * @var \GuzzleHttp\Client The HTTP client instance
     */
    protected Client $client;

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Send a message to a Telegram chat.
     *
     * @param string $chatId The Telegram chat ID
     * @param string $message The message content
     * @param string $botKey The configuration key for the bot (defaults to 'default')
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException If the HTTP request fails
     */
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
