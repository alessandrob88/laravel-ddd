<?php

namespace App\Domain\Invoice\Services;

use App\Domain\Invoice\Exceptions\InvoiceNotFoundException;
use App\Domain\Invoice\Factories\InvoiceModelFactory;
use App\Domain\Invoice\ValueObjects\InvoicePayload;

class CreateInvoiceRowsService extends CreateInvoiceService
{
    public function createRows(InvoicePayload $invoicePayload) : void
    {
        $progressive = $invoicePayload->getProgressive()->getValue();

        if (!$invoice = $this->invoiceRepository->read($progressive))
        {
            throw new InvoiceNotFoundException("Invoice with progressive $progressive does not exists!");
        }
        
        $this->createInvoiceRows($invoicePayload, $invoice->getId());
     
        $this->invoiceRepository->update(InvoiceModelFactory::create(
            $invoice->getId(),
            $invoice->getCustomerId(),
            $invoice->getProgressive(),
            $invoicePayload->getTotal()->getValue(),
            $invoice->getCreatedAt(),
        ));
    }
}