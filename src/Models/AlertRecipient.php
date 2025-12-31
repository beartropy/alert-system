<?php

namespace Beartropy\AlertSystem\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model representing an alert recipient.
 *
 * @property int $id
 * @property int $alert_type_id
 * @property int $alert_channel_id
 * @property string $address
 * @property bool $is_active
 * @property string|null $bot
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Beartropy\AlertSystem\Models\AlertType $type
 * @property-read \Beartropy\AlertSystem\Models\AlertChannel $channel
 */
class AlertRecipient extends Model
{
    protected $fillable = ['alert_type_id', 'alert_channel_id', 'address', 'is_active', 'bot'];

    /**
     * Get the alert type for this recipient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(AlertType::class, 'alert_type_id');
    }

    /**
     * Get the alert channel for this recipient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(AlertChannel::class, 'alert_channel_id');
    }
}
