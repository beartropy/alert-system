<?php

namespace Beartropy\AlertSystem\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model representing an alert type.
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Beartropy\AlertSystem\Models\AlertRecipient[] $recipients
 */
class AlertType extends Model
{
    protected $fillable = ['name'];

    /**
     * Get the recipients subscribed to this alert type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recipients()
    {
        return $this->hasMany(AlertRecipient::class);
    }
}
