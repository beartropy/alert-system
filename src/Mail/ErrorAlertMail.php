<?php

namespace Beartropy\AlertSystem\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ErrorAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $type;
    public string $alertMessage;
    public array $details;
    public ?string $subjectText;

    public function __construct(string $type, string $message, array $details, ?string $subject = null)
    {
        $this->type = $type;
        $this->alertMessage = $message;
        $this->details = $details;
        $this->subjectText = $subject ?? "{$type} Alert";
    }

    public function build()
    {
        $typeSlug = strtolower(str_replace(' ', '_', $this->type));
        $view = view()->exists("alert-system::mail.error_alerts.{$typeSlug}")
            ? "alert-system::mail.error_alerts.{$typeSlug}"
            : "alert-system::mail.error_alerts.default";

        return $this->subject($this->subjectText)
                    ->view($view, [
                        'type' => $this->type,
                        'alertMessage' => $this->alertMessage,
                        'details' => $this->details,
                    ]);
    }
}
