<?php
namespace App\Listeners;

use App\Domain\Invoice\ValueObjects\InvoicePayload;
use App\Events\InvoiceCancel;
use App\Jobs\InvoiceCancelJob;
use Illuminate\Support\Facades\Log;

class InvoiceCancelEventListener
{
    /**
     * Handle the event.
     */
    public function handle(InvoiceCancel $event): void
    {
        Log::info('Invoice cancel event received');
        InvoiceCancelJob::dispatch(
            InvoicePayload::fromArray($event->payload)
        );
    }
}
