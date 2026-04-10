# Alert Facade — AI Reference

## Facade Access
```php
use Beartropy\AlertSystem\Facades\Alert;
Alert::send('type', 'message', ['key' => 'value'], ['mailSubject' => 'Subject']);
```

## Architecture
- Facade accessor: `'alert-system'`
- Resolves to: `AlertService` (singleton)
- Uses `AllChannelsTraits` trait which aggregates: `MailNotification`, `TelegramNotification`, `DiscordNotification`

## AlertService::send() Flow
1. `isEnvironmentAllowed()` — checks `config('alert-system.envs')` against `app()->environment()`
2. `isInCooldown()` — prevents duplicate alerts within `cooldown_minutes`
3. Query `AlertRecipient` where type matches and `is_active = true`
4. For each recipient: `sendAlertViaChannel()` dispatches to the appropriate channel trait method
5. `handleDB()` — logs result to `alert_logs` table (if `db-history` enabled)
6. `handleLog()` — logs via Laravel logger

## Channel Trait Methods
- `mailAlert($recipient, $type, $message, $details, $subject)` — sends `ErrorAlertMail` Mailable
- `telegramAlert($recipient, $type, $message, $details, $subject)` — sends via `TelegramService::sendMessage()`
- `discordAlert($recipient, $type, $message, $details, $subject)` — sends via `DiscordService::sendMessage()`

## Services
- `TelegramService`: Uses GuzzleHttp, supports proxy and SSL verification config per bot
- `DiscordService`: Uses GuzzleHttp, sends to webhook URL, supports proxy and SSL verification

## Common Pitfalls
- Alerts are silently skipped in non-allowed environments — check `envs` config
- Cooldown is per alert type, not per recipient
- `flattenAlertDetails()` converts nested arrays to dot-notation strings for display
