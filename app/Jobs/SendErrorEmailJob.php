<?php

namespace App\Jobs;

use App\Domain\Invoice\ValueObjects\InvoicePayload;
use App\Mail\ErrorEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendErrorEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $jobName;
    public string $errorMessage;
    public InvoicePayload $invoicePayload;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $jobName,
        public string $errorMessage,
        public InvoicePayload $invoicePayload,
    ) {}


    /**
     * Execute the job.
     */
    public function handle()
    {
        $recipient = env('ERROR_MAIL_RECIPIENT');
        Mail::to($recipient)->send(new ErrorEmail(
            $this->jobName,
            $this->errorMessage,
            $this->invoicePayload,
        ));
    }
}
