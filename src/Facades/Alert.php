<?php

namespace Beartropy\AlertSystem\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for the Alert System Service.
 *
 * @method static void send(string $type, string $message, array $details = [], array $options = []) Send an alert.
 *
 * @see \Beartropy\AlertSystem\AlertService
 */
class Alert extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'alert-system';
    }
}
