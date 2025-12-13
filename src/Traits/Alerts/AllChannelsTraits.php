<?php

namespace Beartropy\AlertSystem\Traits\Alerts;

use Beartropy\AlertSystem\Traits\Alerts\Channels\MailNotification;
use Beartropy\AlertSystem\Traits\Alerts\Channels\TelegramNotification;
use Beartropy\AlertSystem\Traits\Alerts\Channels\DiscordNotification;

trait AllChannelsTraits
{
    use MailNotification;
    use TelegramNotification;
    use DiscordNotification;

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
