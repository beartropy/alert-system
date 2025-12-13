<?php

return [
    'cooldown_minutes' => 10,
    'telegram' => [
        'bots' => [
            'my_bot' => [
                'token' => env('TELEGRAM_ALERTS_BOT_TOKEN'),
                'proxy' => env('TELEGRAM_ALERTS_PROXY', null),
                'verify' => env('TELEGRAM_ALERTS_VERIFY', true),
            ]
        ],
        'default' => 'my_bot',
    ],
    'discord' => [
        'bots' => [
            'my_bot' => [
                'webhook' => env('DISCORD_ALERTS_WEBHOOK_URL'),
                'proxy' => env('DISCORD_ALERTS_PROXY', null),
                'verify' => env('DISCORD_ALERTS_VERIFY', true),
            ]
        ],
        'default' => 'my_bot',
    ],
    'envs' => ["production"],
    'layout' => 'layouts.app',
    'publish_routes' => true,
    'route_prefix' => 'admin/alerts',
    'route_middlewares' => ['web', 'auth'],
    'db-history' => env('ALERT_SYSTEM_DB_HISTORY', true),
    'logging' => [
        'enabled' => true,
        'channel' => 'daily',
        'level' => 'info',
    ]
];
