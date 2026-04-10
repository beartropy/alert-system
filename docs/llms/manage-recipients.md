# ManageRecipients — AI Reference

## Component Registration
```blade
@livewire('alert-system.manage-recipients')
```

## Architecture
- `ManageRecipients` → extends `YATBaseTable` (Beartropy Tables)
- Namespace: `Beartropy\AlertSystem\Livewire`
- Model: `AlertRecipient`
- Table name: `ManageRecipients`

## Public Properties
| Property | Type | Default |
|----------|------|---------|
| `tableName` | `string` | `'ManageRecipients'` |
| `model` | `string` | `AlertRecipient::class` |
| `selectedRecipient` | `?AlertRecipient` | `null` |
| `createRecipient` | `bool` | `false` |
| `openEditRecipientModal` | `bool` | `false` |
| `recipientType` | `string` | `''` |
| `recipientChannel` | `string` | `''` |
| `recipientAddress` | `string` | `''` |
| `recipientBot` | `string` | `''` |
| `recipientIsActive` | `bool` | `true` |
| `types` | `array` | `[]` |
| `channels` | `array` | `[]` |
| `openDeleteConfirmationModal` | `bool` | `false` |
| `recipientToDeleteId` | `?int` | `null` |

## Key Methods
- `settings()` — Configures table with create button
- `columns()` — Type, Channel, Address, Bot, Active, Actions columns
- `openNewRecipientModal()` — Resets form and opens create modal
- `storeRecipient()` — Validates and creates new recipient
- `editRecipient($id)` — Opens edit modal with loaded recipient
- `updateRecipient()` — Validates and updates recipient
- `confirmDeleteRecipient($id)` — Opens delete confirmation modal
- `deleteRecipient()` — Deletes recipient

## Validation Rules
- `recipientType`: required, exists in alert_types
- `recipientChannel`: required, exists in alert_channels
- `recipientAddress`: required, string, max 255
- `recipientBot`: nullable, string
