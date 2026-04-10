# Configuration

Full configuration reference for `config/alert-system.php`.

## Options

| Key | Type | Default | Description |
|-----|------|---------|-------------|
| `cooldown_minutes` | `int` | `10` | Minutes to wait before resending the same alert |
| `envs` | `array` | `["production"]` | Environments where alerts are sent |
| `layout` | `string` | `layouts.app` | Blade layout for Livewire management views |
| `publish_routes` | `bool` | `true` | Register package routes |
| `route_prefix` | `string` | `admin/alerts` | Route prefix for management pages |
| `route_middlewares` | `array` | `['web', 'auth']` | Middleware for management routes |
| `db-history` | `bool` | `true` | Log sent alerts to database |
| `logging.enabled` | `bool` | `true` | Enable Laravel log output |
| `logging.channel` | `string` | `daily` | Laravel log channel |
| `logging.level` | `string` | `info` | Laravel log level |

## Telegram Configuration

```php
'telegram' => [
    'bots' => [
        'my_bot' => [
            'token' => env('TELEGRAM_ALERTS_BOT_TOKEN'),
            'proxy' => env('TELEGRAM_ALERTS_PROXY', null),
            'verify' => env('TELEGRAM_ALERTS_VERIFY', true),
        ],
    ],
    'default' => 'my_bot',
],
```

## Discord Configuration

```php
'discord' => [
    'bots' => [
        'my_bot' => [
            'webhook' => env('DISCORD_ALERTS_WEBHOOK_URL'),
            'proxy' => env('DISCORD_ALERTS_PROXY', null),
            'verify' => env('DISCORD_ALERTS_VERIFY', true),
        ],
    ],
    'default' => 'my_bot',
],
```

## Routes

| Method | URI | Name | Component |
|--------|-----|------|-----------|
| GET | `/admin/alerts/recipients` | `alerts.recipients` | ManageRecipients |
| GET | `/admin/alerts/types` | `alerts.types` | ManageTypes |
| GET | `/admin/alerts/channels` | `alerts.channels` | ManageChannels |
| GET | `/admin/alerts/dashboard` | `alerts.dashboard` | ManageLogs |
