# ManageTypes — AI Reference

## Component Registration
```blade
@livewire('alert-system.manage-types')
```

## Architecture
- `ManageTypes` → extends `YATBaseTable` (Beartropy Tables)
- Namespace: `Beartropy\AlertSystem\Livewire`
- Model: `AlertType`
- Table name: `ManageTypes`

## Public Properties
| Property | Type | Default |
|----------|------|---------|
| `tableName` | `string` | `'ManageTypes'` |
| `model` | `string` | `AlertType::class` |
| `selectedType` | `?AlertType` | `null` |
| `openEditTypeModal` | `bool` | `false` |
| `typeName` | `string` | `''` |

## Key Methods
- `settings()` — Configures table title, bulk actions
- `columns()` — ID, Name, Actions columns
- `editType($id)` — Opens edit modal with loaded type
- `updateType()` — Validates and saves type name
- `options()` — Export actions: `export_all`, `export_filtered`, `export_selected`
