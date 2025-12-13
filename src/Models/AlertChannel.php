<?php

namespace Beartropy\AlertSystem\Models;

use Illuminate\Database\Eloquent\Model;

class AlertChannel extends Model
{
    protected $fillable = ['name'];

    public function recipients()
    {
        return $this->hasMany(AlertRecipient::class);
    }
}
