<?php

namespace Beartropy\AlertSystem\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable for sending error alerts.
 *
 * This class builds the email content for an alert, dynamically selecting
 * a view based on the alert type if available, or falling back to a default view.
 */
class ErrorAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string The type of alert (e.g., 'system_error')
     */
    public string $type;

    /**
     * @var string The main message of the alert
     */
    public string $alertMessage;

    /**
     * @var array<string, mixed> Additional details about the alert
     */
    public array $details;

    /**
     * @var string|null The email subject line
     */
    public ?string $subjectText;

    /**
     * Create a new message instance.
     *
     * @param string $type The type of alert
     * @param string $message The alert message
     * @param array<string, mixed> $details Additional details
     * @param string|null $subject Optional custom subject. Defaults to "{$type} Alert".
     */
    public function __construct(string $type, string $message, array $details, ?string $subject = null)
    {
        $this->type = $type;
        $this->alertMessage = $message;
        $this->details = $details;
        $this->subjectText = $subject ?? "{$type} Alert";
    }

    /**
     * Build the message.
     *
     * This method selects the view based on the alert type slug.
     * Use `alert-system::mail.error_alerts.{$typeSlug}` if it exists,
     * otherwise `alert-system::mail.error_alerts.default`.
     *
     * @return $this
     */
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
