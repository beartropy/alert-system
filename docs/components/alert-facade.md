# Alert Facade

Send alerts to configured recipients via multiple channels (Mail, Telegram, Discord).

## Basic Usage

```php
use Beartropy\AlertSystem\Facades\Alert;

Alert::send('server_error', 'Database connection failed', [
    'server' => 'db-primary',
    'error_code' => 500,
]);
```

## Methods

| Method | Signature | Description |
|--------|-----------|-------------|
| `send` | `(string $type, string $message, array $details = [], array $options = [])` | Send alert to all active recipients of the given type |

## Options

| Key | Type | Description |
|-----|------|-------------|
| `mailSubject` | `string` | Custom email subject line |

## How It Works

1. Checks if the current environment is allowed (config `envs`)
2. Checks cooldown to prevent duplicate alerts
3. Finds all active recipients for the given alert type
4. Sends via each recipient's configured channel (Mail, Telegram, Discord)
5. Logs the result to the database (if `db-history` enabled) and Laravel log

## Channels

### Mail
Sends an HTML email using `ErrorAlertMail` Mailable with alert type, message, and details.

### Telegram
Sends an HTML-formatted message via Telegram Bot API. Supports multiple bots and proxy configuration.

### Discord
Sends a markdown-formatted message via Discord webhook. Supports multiple bots and proxy configuration.

## Environment Restriction

By default, alerts only send in `production`. Configure in `config/alert-system.php`:

```php
'envs' => ['production', 'staging'],
```

## Cooldown

Prevents the same alert from being sent repeatedly within the configured minutes:

```php
'cooldown_minutes' => 10,
```
