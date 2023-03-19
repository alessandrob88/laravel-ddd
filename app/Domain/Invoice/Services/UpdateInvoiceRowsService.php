<?php

namespace App\Domain\Invoice\Services;

use App\Domain\Invoice\Exceptions\InvoiceNotFoundException;
use App\Domain\Invoice\ValueObjects\InvoicePayload;

class UpdateInvoiceRowsService extends InvoiceService
{
    public function update(InvoicePayload $invoicePayload) : void
    {
        $progressive = $this->getInvoiceProgressiveFromPayload($invoicePayload);

        if (empty($invoice = $this->getInvoice($progressive)))
        {
            throw new InvoiceNotFoundException("Invoice with progressive $progressive does not exists!");
        }

        $invoiceRows = $this->mapInvoiceRowsToModel($invoicePayload, $invoice->getId());
        
        $this->invoiceRowsRepository->bulkUpdate(
            $this->filterInvoiceRowsByEvent($invoiceRows, 'update')
        );

        $this->invoiceRowsRepository->bulkDelete(
            $this->filterInvoiceRowsByEvent($invoiceRows, 'delete')
        );
    }

    private function filterInvoiceRowsByEvent(array $invoiceRows, string $event) : array
    {
        return array_filter($invoiceRows, function($invoiceRow) use ($event)
        {
            return $invoiceRow->getEvent() === $event;
        });
    }
}