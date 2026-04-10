# Manage Recipients

A Livewire table component for managing alert recipients — linking alert types to channels with specific addresses.

## Usage

```blade
@livewire('alert-system.manage-recipients')
```

Route: `GET /admin/alerts/recipients` (name: `alerts.recipients`)

## Features

- Create, edit, and delete recipients
- Link alert types to channels with a destination address
- Configure bot name for Telegram/Discord channels
- Toggle active/inactive status
- Delete confirmation modal
- Bulk export to Excel

## Recipient Fields

| Field | Type | Description |
|-------|------|-------------|
| `alert_type_id` | FK | The alert type this recipient responds to |
| `alert_channel_id` | FK | The channel to send through (Mail, Telegram, Discord) |
| `address` | `string` | Destination: email, chat ID, or webhook URL |
| `bot` | `string\|null` | Bot key name for Telegram/Discord (from config) |
| `is_active` | `bool` | Whether this recipient is active |
