<?php

namespace Beartropy\AlertSystem\Traits\Alerts;

use Beartropy\AlertSystem\Traits\Alerts\Channels\MailNotification;
use Beartropy\AlertSystem\Traits\Alerts\Channels\TelegramNotification;
use Beartropy\AlertSystem\Traits\Alerts\Channels\DiscordNotification;

/**
 * Trait aggregating all channel notification traits.
 *
 * This trait composes specific channel notification capabilities and provides
 * utility methods for processing alert details.
 */
trait AllChannelsTraits
{
    use MailNotification;
    use TelegramNotification;
    use DiscordNotification;

    /**
     * Flatten a multidimensional array of alert details into a single-level array.
     *
     * Nested keys are concatenated with dots (e.g., 'parent.child').
     *
     * @param array<string, mixed> $details The details array to flatten
     * @return array<string, string> The flattened array with string values
     */
    public function flattenAlertDetails(array $details): array
    {
        $flattened = [];

        $flatten = function ($array, $prefix = '') use (&$flattened, &$flatten) {
            foreach ($array as $key => $value) {
                $fullKey = $prefix === '' ? $key : "{$prefix}.{$key}";

                if (is_array($value) || is_object($value)) {
                    $flatten((array) $value, $fullKey);
                } else {
                    $flattened[$fullKey] = (string) $value;
                }
            }
        };

        $flatten($details);

        return $flattened;
    }
}
