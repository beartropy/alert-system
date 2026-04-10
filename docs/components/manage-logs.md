# Manage Logs

A Livewire table component for viewing alert logs with filters.

## Usage

```blade
@livewire('alert-system.manage-logs')
```

Route: `GET /admin/alerts/dashboard` (name: `alerts.dashboard`)

## Features

- View all sent alerts with status, type, channel, and timestamp
- Filter by: status (success/failure), type, channel, date range
- View full alert details in a modal (message, details, error)
- Bulk export to Excel

## Log Fields

| Field | Description |
|-------|-------------|
| `type` | Alert type name |
| `channel` | Channel used (mail, telegram, discord) |
| `address` | Destination address |
| `bot` | Bot key used (if applicable) |
| `status` | `success` or `failure` |
| `subject` | Email subject (if mail) |
| `message` | Alert message |
| `details` | JSON details array |
| `error_message` | Error message on failure |
| `sent_at` | Timestamp when sent |
