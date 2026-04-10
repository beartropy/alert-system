# Alert Examples - Beartropy Alert System

## Basic Alert

```php
use Beartropy\AlertSystem\Facades\Alert;

Alert::send('server_error', 'Database connection timeout', [
    'server' => 'db-replica-02',
    'timeout' => '30s',
]);
```

## Alert with Custom Email Subject

```php
Alert::send('payment_failed', 'Payment processing failed', [
    'order_id' => $order->id,
    'amount' => $order->total,
    'gateway' => 'stripe',
    'error' => $exception->getMessage(),
], [
    'mailSubject' => "Payment Failed: Order #{$order->id}",
]);
```

## In Exception Handler

```php
// bootstrap/app.php
->withExceptions(function (Exceptions $exceptions) {
    $exceptions->report(function (Throwable $e) {
        if ($e instanceof \Illuminate\Database\QueryException) {
            Alert::send('database_error', $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'sql' => $e->getSql() ?? 'N/A',
            ]);
        }
    });
})
```

## In a Scheduled Command

```php
// app/Console/Commands/CheckDiskSpace.php
class CheckDiskSpace extends Command
{
    protected $signature = 'monitor:disk';

    public function handle(): void
    {
        $free = disk_free_space('/') / 1073741824; // GB

        if ($free < 5) {
            Alert::send('disk_space', "Low disk space: {$free}GB remaining", [
                'threshold' => '5GB',
                'current' => round($free, 2) . 'GB',
            ]);
        }
    }
}
```

## Seeding Alert Types and Channels

```php
use Beartropy\AlertSystem\Models\AlertType;
use Beartropy\AlertSystem\Models\AlertChannel;
use Beartropy\AlertSystem\Models\AlertRecipient;

class AlertSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['server_error', 'payment_failed', 'user_registered', 'disk_space'];
        foreach ($types as $name) {
            AlertType::firstOrCreate(['name' => $name]);
        }

        $mail = AlertChannel::firstOrCreate(['name' => 'mail']);
        $telegram = AlertChannel::firstOrCreate(['name' => 'telegram']);

        // Send server errors via both mail and telegram
        $serverError = AlertType::where('name', 'server_error')->first();

        AlertRecipient::firstOrCreate([
            'alert_type_id' => $serverError->id,
            'alert_channel_id' => $mail->id,
            'address' => 'ops@example.com',
        ]);

        AlertRecipient::firstOrCreate([
            'alert_type_id' => $serverError->id,
            'alert_channel_id' => $telegram->id,
            'address' => '-1001234567890', // Telegram chat ID
            'bot' => 'my_bot',
        ]);
    }
}
```
