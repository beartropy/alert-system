# Beartropy Alert System - Universal AI Assistant Guide

> This guide helps AI assistants generate correct code using Beartropy Alert System for Laravel applications.

## Overview

**Beartropy Alert System** is a multi-channel alerting package with Mail, Telegram, and Discord support, plus a Livewire management UI.

## Sending Alerts

```php
use Beartropy\AlertSystem\Facades\Alert;

Alert::send('server_error', 'Database connection failed', [
    'server' => 'db-primary',
    'error_code' => 500,
], [
    'mailSubject' => 'Critical: DB Down',
]);
```

### Parameters
1. `string $type` — Alert type name (must exist in `alert_types` table)
2. `string $message` — Alert message
3. `array $details` — Additional key-value details (optional)
4. `array $options` — Options like `mailSubject` (optional)

## Channels

### Mail
Sends HTML email via `ErrorAlertMail` Mailable.

### Telegram
Sends HTML-formatted message via Telegram Bot API.
- Config: `alert-system.telegram.bots.{name}.token`
- Recipient address = Telegram chat ID

### Discord
Sends markdown-formatted message via webhook.
- Config: `alert-system.discord.bots.{name}.webhook`
- Recipient address = webhook URL

## Management UI

4 Livewire components for admin:

```blade
@livewire('alert-system.manage-types')       {{-- Alert types CRUD --}}
@livewire('alert-system.manage-channels')    {{-- Channel types CRUD --}}
@livewire('alert-system.manage-recipients')  {{-- Recipients CRUD --}}
@livewire('alert-system.manage-logs')        {{-- View sent alerts --}}
```

### Routes (prefix: admin/alerts)
- `/admin/alerts/types` — Manage alert types
- `/admin/alerts/channels` — Manage channels
- `/admin/alerts/recipients` — Manage recipients
- `/admin/alerts/dashboard` — View logs

## Configuration

```php
// config/alert-system.php
'cooldown_minutes' => 10,          // Prevent duplicate alerts
'envs' => ['production'],          // Only send in these environments
'db-history' => true,              // Log to database
'route_prefix' => 'admin/alerts',
'route_middlewares' => ['web', 'auth'],
```

## Key Concepts

- **Alert Type**: Category of alert (e.g., "server_error", "payment_failed")
- **Channel**: Delivery method (mail, telegram, discord)
- **Recipient**: Links a type to a channel with a destination address
- **Cooldown**: Prevents the same alert type from firing repeatedly

## Database Tables

- `alert_types` — id, name
- `alert_channels` — id, name
- `alert_recipients` — id, alert_type_id, alert_channel_id, address, bot, is_active
- `alert_logs` — id, type, channel, address, status, message, details, error_message, sent_at
