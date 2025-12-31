<?php

namespace Beartropy\AlertSystem\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model representing an alert log entry.
 *
 * @property int $id
 * @property string $type
 * @property string $channel
 * @property string $address
 * @property string $status
 * @property string $subject
 * @property string $message
 * @property array $details
 * @property string|null $error_message
 * @property \Illuminate\Support\Carbon $sent_at
 * @property string|null $bot
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class AlertLog extends Model
{
    protected $fillable = [
        'type',
        'channel',
        'address',
        'status',
        'subject',
        'message',
        'details',
        'error_message',
        'sent_at',
        'bot'
    ];

    protected $casts = [
        'details' => 'array',
        'sent_at' => 'datetime',
    ];
}
