# Configuration — AI Reference

## Config File
`config/alert-system.php`

## All Keys

| Key | Type | Default | Env Var |
|-----|------|---------|---------|
| `cooldown_minutes` | `int` | `10` | — |
| `envs` | `array` | `["production"]` | — |
| `layout` | `string` | `'layouts.app'` | — |
| `publish_routes` | `bool` | `true` | — |
| `route_prefix` | `string` | `'admin/alerts'` | — |
| `route_middlewares` | `array` | `['web', 'auth']` | — |
| `db-history` | `bool` | `true` | `ALERT_SYSTEM_DB_HISTORY` |
| `logging.enabled` | `bool` | `true` | — |
| `logging.channel` | `string` | `'daily'` | — |
| `logging.level` | `string` | `'info'` | — |
| `telegram.bots.{name}.token` | `string` | — | `TELEGRAM_ALERTS_BOT_TOKEN` |
| `telegram.bots.{name}.proxy` | `?string` | `null` | `TELEGRAM_ALERTS_PROXY` |
| `telegram.bots.{name}.verify` | `bool` | `true` | `TELEGRAM_ALERTS_VERIFY` |
| `telegram.default` | `string` | `'my_bot'` | — |
| `discord.bots.{name}.webhook` | `string` | — | `DISCORD_ALERTS_WEBHOOK_URL` |
| `discord.bots.{name}.proxy` | `?string` | `null` | `DISCORD_ALERTS_PROXY` |
| `discord.bots.{name}.verify` | `bool` | `true` | `DISCORD_ALERTS_VERIFY` |
| `discord.default` | `string` | `'my_bot'` | — |

## Database Tables

### alert_types
| Column | Type |
|--------|------|
| `id` | bigint PK |
| `name` | string (unique) |
| `timestamps` | — |

### alert_channels
| Column | Type |
|--------|------|
| `id` | bigint PK |
| `name` | string |
| `timestamps` | — |

### alert_recipients
| Column | Type |
|--------|------|
| `id` | bigint PK |
| `alert_type_id` | FK → alert_types (cascade) |
| `alert_channel_id` | FK → alert_channels (cascade) |
| `address` | string |
| `bot` | string (nullable) |
| `is_active` | bool (default true) |
| `timestamps` | — |

### alert_logs
| Column | Type |
|--------|------|
| `id` | bigint PK |
| `type` | string |
| `channel` | string |
| `address` | string |
| `bot` | string (nullable) |
| `status` | string (success/failure) |
| `subject` | string (nullable) |
| `message` | text |
| `details` | JSON |
| `error_message` | text (nullable) |
| `sent_at` | timestamp (nullable) |
| `timestamps` | — |

## Common Pitfalls
- `envs` must include the current environment or alerts are silently skipped
- `cooldown_minutes` applies globally per alert type — set to 0 to disable
- `publish_routes` must be true for the management UI to be accessible
- Bot keys in recipients must match keys in config `telegram.bots` or `discord.bots`
