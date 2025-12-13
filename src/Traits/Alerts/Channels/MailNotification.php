<?php

namespace Beartropy\AlertSystem\Traits\Alerts\Channels;

use Illuminate\Support\Facades\Mail;
use Beartropy\AlertSystem\Mail\ErrorAlertMail;

trait MailNotification
{
    public function mailAlert($recipient, $type, $message, $details = [], $subject = null) {
        Mail::to($recipient->address)->send(new ErrorAlertMail($type, $message, $details, $subject));
    }
}
