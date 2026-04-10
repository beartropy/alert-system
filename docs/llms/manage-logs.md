# ManageLogs — AI Reference

## Component Registration
```blade
@livewire('alert-system.manage-logs')
```

## Architecture
- `ManageLogs` → extends `YATBaseTable` (Beartropy Tables)
- Namespace: `Beartropy\AlertSystem\Livewire`
- Model: `AlertLog`
- Table name: `ManageLogs`

## Public Properties
| Property | Type | Default |
|----------|------|---------|
| `tableName` | `string` | `'ManageLogs'` |
| `model` | `string` | `AlertLog::class` |
| `selectedLog` | `?AlertLog` | `null` |
| `openInfoLogModal` | `bool` | `false` |

## Key Methods
- `settings()` — Configures table title and sorting
- `columns()` — Type, Channel, Address, Status, Sent At, Actions columns
- `filters()` — Status (select), Type (select), Channel (select), Sent At (date range)
- `showDetails($id)` — Opens detail modal with full alert info
- `options()` — Export actions

## AlertLog Model Casts
- `details` → `array` (JSON)
- `sent_at` → `datetime`
