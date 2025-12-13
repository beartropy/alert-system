<?php

namespace Beartropy\AlertSystem\Models;

use Illuminate\Database\Eloquent\Model;

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
