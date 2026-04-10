# ManageChannels — AI Reference

## Component Registration
```blade
@livewire('alert-system.manage-channels')
```

## Architecture
- `ManageChannels` → extends `YATBaseTable` (Beartropy Tables)
- Namespace: `Beartropy\AlertSystem\Livewire`
- Model: `AlertChannel`
- Table name: `ManageChannels`

## Public Properties
| Property | Type | Default |
|----------|------|---------|
| `tableName` | `string` | `'ManageChannels'` |
| `model` | `string` | `AlertChannel::class` |
| `selectedChannel` | `?AlertChannel` | `null` |
| `openEditChannelModal` | `bool` | `false` |
| `channelName` | `string` | `''` |

## Key Methods
- `settings()` — Configures table title, bulk actions
- `columns()` — ID, Name, Actions columns
- `editChannel($id)` — Opens edit modal with loaded channel
- `updateChannel()` — Validates and saves channel name
- `options()` — Export actions
