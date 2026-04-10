# AI Assistant Support for Beartropy Alert System

Beartropy Alert System includes AI assistant integration to help you configure and use alerts.

## Supported AI Assistants

### Claude Code / Cursor / Other AI Tools
- Universal guide with API reference
- Cursor rules for component suggestions
- Copy-paste ready examples

## Directory Structure

```
beartropy/alert-system/
└── docs/
    ├── llms/                      # LLM reference docs
    ├── components/                # User reference docs
    └── ai-assistants/
        ├── README.md              # This file
        ├── BEARTROPY_GUIDE.md     # Universal AI guide
        ├── cursor/
        │   └── .cursorrules       # Cursor configuration
        └── examples/
            └── alerts.md          # Usage examples
```

## Quick Start

### Using with Cursor

```bash
cp vendor/beartropy/alert-system/docs/ai-assistants/cursor/.cursorrules .cursorrules
```

### Using with Other AI Tools

Point your AI assistant to:
```
vendor/beartropy/alert-system/docs/ai-assistants/BEARTROPY_GUIDE.md
```

## License

MIT License.
