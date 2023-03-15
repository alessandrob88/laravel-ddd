<?php
namespace App\Listeners;

use App\Domain\Invoice\ValueObjects\InvoicePayload;
use App\Events\InvoiceCreate;
use App\Jobs\InvoiceCreateJob;
use Illuminate\Support\Facades\Log;

class InvoiceCreateEventListener
{
    /**
     * Handle the event.
     */
    public function handle(InvoiceCreate $event): void
    {
        Log::info('Invoice create event received'); 
        InvoiceCreateJob::dispatch(
            InvoicePayload::fromArray($event->payload)
        );
    }
}
