<?php

namespace App\Domain\Invoice\Services;

use App\Domain\Invoice\Exceptions\InvoiceNotFoundException;
use App\Domain\Invoice\ValueObjects\InvoicePayload;

class DeleteInvoiceService extends InvoiceService 
{
    public function delete(InvoicePayload $invoicePayload) : void
    {
        $progressive = $this->getInvoiceProgressiveFromPayload($invoicePayload);

        if (empty($this->getInvoice($progressive)))
        {
            throw new InvoiceNotFoundException("Invoice with progressive $progressive does not exists!");
        }

        $this->invoiceRepository->delete($progressive);
    }
}