---
name: bt-alert-system-setup
description: Help users install and configure Beartropy Alert System in their Laravel projects
version: 1.0.0
author: Beartropy
tags: [beartropy, alerts, installation, setup, configuration, telegram, discord, email]
---

# Beartropy Alert System Setup Guide

You are an expert in helping users install and configure Beartropy Alert System in their Laravel applications.

---

## Requirements

- PHP >= 8.1
- Laravel >= 11.x
- Livewire >= 3.x
- beartropy/ui (installed automatically)
- beartropy/tables (installed automatically)

---

## Installation

### Step 1: Install via Composer

```bash
composer require beartropy/alert-system
```

### Step 2: Publish Config and Migrations

```bash
php artisan vendor:publish --provider="Beartropy\AlertSystem\AlertSystemServiceProvider"
php artisan migrate
```

### Step 3: Seed Default Data

```bash
php artisan db:seed --class=AlertSystemSeeder
```

Or create types and channels manually via the management UI.

---

## Configuration

Edit `config/alert-system.php`:

```php
return [
    'cooldown_minutes' => 10,           // Prevent duplicate alerts
    'envs' => ['production'],           // Only send in these environments
    'route_prefix' => 'admin/alerts',   // Management UI route prefix
    'route_middlewares' => ['web', 'auth'],
    'db-history' => true,               // Log alerts to database

    'telegram' => [
        'bots' => [
            'my_bot' => [
                'token' => env('TELEGRAM_ALERTS_BOT_TOKEN'),
            ],
        ],
        'default' => 'my_bot',
    ],

    'discord' => [
        'bots' => [
            'my_bot' => [
                'webhook' => env('DISCORD_ALERTS_WEBHOOK_URL'),
            ],
        ],
        'default' => 'my_bot',
    ],
];
```

---

## Sending Alerts

```php
use Beartropy\AlertSystem\Facades\Alert;

Alert::send('server_error', 'Database connection failed', [
    'server' => 'db-primary',
    'error_code' => 500,
]);
```

### Parameters

1. `string $type` — Alert type name (must exist in `alert_types` table)
2. `string $message` — Alert message
3. `array $details` — Additional key-value details (optional)
4. `array $options` — Options like `['mailSubject' => 'Subject']` (optional)

---

## Management UI

4 Livewire components available at `/admin/alerts/*`:

| Route | Component | Description |
|---|---|---|
| `/admin/alerts/types` | `alert-system.manage-types` | Manage alert types |
| `/admin/alerts/channels` | `alert-system.manage-channels` | Manage channels |
| `/admin/alerts/recipients` | `alert-system.manage-recipients` | Manage recipients |
| `/admin/alerts/dashboard` | `alert-system.manage-logs` | View sent alerts |

---

## Channels

### Mail
Standard Laravel email. Recipient address = email address.

### Telegram
Set `TELEGRAM_ALERTS_BOT_TOKEN` in `.env`. Recipient address = chat ID.

### Discord
Set `DISCORD_ALERTS_WEBHOOK_URL` in `.env`. Recipient address = webhook URL.

---

## Troubleshooting

### Alerts not sending
- Check `envs` config — alerts only send in listed environments
- Check `cooldown_minutes` — same alert type is throttled
- Verify recipients exist and are active for the given alert type

### Management UI not showing
- Ensure `publish_routes` is `true` in config
- Check middleware in `route_middlewares` config
