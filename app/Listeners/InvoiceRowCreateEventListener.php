<?php
namespace App\Listeners;

use App\Domain\Invoice\ValueObjects\InvoicePayload;
use App\Events\InvoiceRowCreate;
use Illuminate\Support\Facades\Log;

class InvoiceRowCreateEventListener
{
    /**
     * Handle the event.
     */
    public function handle(InvoiceRowCreate $event): void
    {
        Log::info('Invoice row create event received');
        InvoiceRowCreate::dispatch(
            InvoicePayload::fromArray($event->payload)
        );
    }
}
