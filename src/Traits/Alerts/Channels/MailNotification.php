<?php

namespace Beartropy\AlertSystem\Traits\Alerts\Channels;

use Illuminate\Support\Facades\Mail;
use Beartropy\AlertSystem\Mail\ErrorAlertMail;

/**
 * Trait for sending Mail notifications.
 */
trait MailNotification
{
    /**
     * Send an alert notification via Email.
     *
     * @param \Beartropy\AlertSystem\Models\AlertRecipient|object $recipient The recipient object with an email address
     * @param string $type The alert type
     * @param string $message The alert message
     * @param array<string, mixed> $details Additional details
     * @param string|null $subject Optional email subject
     * @return void
     */
    public function mailAlert($recipient, $type, $message, $details = [], $subject = null)
    {
        Mail::to($recipient->address)->send(new ErrorAlertMail($type, $message, $details, $subject));
    }
}
