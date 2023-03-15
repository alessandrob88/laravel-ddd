<?php
namespace App\Listeners;

use App\Domain\Invoice\ValueObjects\InvoicePayload;
use App\Events\InvoiceRowUpdate;
use Illuminate\Support\Facades\Log;

class InvoiceRowUpdateEventListener
{
    /**
     * Handle the event.
     */
    public function handle(InvoiceRowUpdate $event): void
    {
        Log::info('Invoice row update event received');
        InvoiceRowUpdate::dispatch(
            InvoicePayload::fromArray($event->payload)
        );
    }
}
