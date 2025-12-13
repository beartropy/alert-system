<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $type }} Alert</title>
    <style>
        .key {
            color: #111827 !important; /* black for light mode */
        }
        .value {
            color: #ca4747 !important; /* red for both modes */
        }
        @media (prefers-color-scheme: dark) {
            body {
                background-color: #1f2937 !important;
                color: #f3f4f6 !important;
            }
            .container {
                background-color: #374151 !important;
                border-color: #4b5563 !important;
            }
            .header {
                border-bottom-color: #60a5fa !important;
            }
            .message, .header h1 {
                color: #f3f4f6 !important;
            }
            .details {
                background-color: #4b5563 !important;
                color: #f3f4f6 !important;
            }
            .key {
                color: #f3f4f6 !important; /* white for dark mode */
            }
            .value {
                color: #fca5a5 !important; /* softer red for dark mode */
            }
            .footer {
                color: #9ca3af !important;
            }
        }
    </style>
</head>
<body style="background-color: #ffffff; color: #111827; padding: 20px;" bgcolor="#ffffff" text="#111827">
    <div class="container" style="max-width: 600px; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px; margin: 0 auto;" bgcolor="#ffffff">

        <div class="header" style="border-bottom: 2px solid #3b82f6; padding-bottom: 8px; margin-bottom: 20px;">
            <h1 style="font-size: 20px; margin: 0; color: #111827;">{{ $type }} Alert</h1>
        </div>

        <div class="message" style="font-size: 16px; margin-bottom: 20px; color: #111827;">
            {!! nl2br(e($alertMessage)) !!}
        </div>

        @if (!empty($details))
            <div class="details" style="background-color: #f3f4f6; border-radius: 6px; padding: 16px; font-family: monospace; font-size: 14px; white-space: normal; word-break: break-word; color: #111827;">
                <ul style="list-style-type: disc; padding-left: 20px; margin: 0;">
                    @foreach ($details as $function => $error)
                        <li style="margin: 6px 0;">
                            <span class="key">{{ $function }}:</span>
                            <span class="value">{{ $error }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="footer" style="margin-top: 30px; font-size: 12px; color: #6b7280; text-align: center;">
            System Alert Notification | <a href="{{ config('app.url') }}" style="color: #3b82f6; text-decoration: none;">{{ config('app.name') }}</a>
        </div>
    </div>
</body>
</html>