<?php

namespace App\Jobs;

use App\Domain\Invoice\ValueObjects\InvoicePayload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InvoiceRowUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public InvoicePayload $invoicePayload,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
