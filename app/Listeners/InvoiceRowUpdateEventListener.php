<?php
namespace App\Listeners;

use App\Domain\Invoice\ValueObjects\InvoicePayload;
use App\Events\InvoiceRowUpdate;
use App\Jobs\InvoiceRowUpdateJob;
use Illuminate\Support\Facades\Log;

class InvoiceRowUpdateEventListener
{
    /**
     * Handle the event.
     */
    public function handle(InvoiceRowUpdate $event): void
    {
        Log::info('Invoice row update event received');
        InvoiceRowUpdateJob::dispatch(
            InvoicePayload::fromArray($event->payload)
        );
    }
}
