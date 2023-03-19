<?php

namespace App\Mail;

use App\Domain\Invoice\ValueObjects\InvoicePayload;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ErrorEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param $errorMessage
     */
    public function __construct(
        public string $jobName,
        public string $errorMessage,
        public InvoicePayload $invoicePayload,
    ) {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.error', [
            'job' => $this->jobName,
            'errorMessage' => $this->errorMessage,
            'payload' => json_encode($this->invoicePayload->toArray()),
        ])->subject('Error occurred');
    }
}
