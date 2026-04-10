---
name: bt-alert-system-component
description: Get detailed information and examples for Beartropy Alert System components and API
version: 1.0.0
author: Beartropy
tags: [beartropy, alerts, components, documentation, examples]
---

# Beartropy Alert System Component Helper

You are an expert in Beartropy Alert System. Use this guide to help users configure alerts and use the management UI.

---

## Quick Reference

| Task | How |
|---|---|
| Send an alert | `Alert::send('type', 'message', ['details'], ['options'])` |
| Manage types | `@livewire('alert-system.manage-types')` |
| Manage channels | `@livewire('alert-system.manage-channels')` |
| Manage recipients | `@livewire('alert-system.manage-recipients')` |
| View logs | `@livewire('alert-system.manage-logs')` |

## Channels

- **Mail**: Standard Laravel email
- **Telegram**: Bot API (set `TELEGRAM_ALERTS_BOT_TOKEN`)
- **Discord**: Webhook (set `DISCORD_ALERTS_WEBHOOK_URL`)
