<?php

namespace Beartropy\AlertSystem\Models;

use Illuminate\Database\Eloquent\Model;

class AlertRecipient extends Model
{
    protected $fillable = ['alert_type_id', 'alert_channel_id', 'address','is_active', 'bot'];

    public function type()
    {
        return $this->belongsTo(AlertType::class,'alert_type_id');
    }

    public function channel()
    {
        return $this->belongsTo(AlertChannel::class,'alert_channel_id');
    }
}
