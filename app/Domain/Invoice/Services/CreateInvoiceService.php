<?php

namespace App\Domain\Invoice\Services;

use App\Domain\Invoice\Exceptions\InvoiceAlreadyExistsException;
use App\Domain\Invoice\Factories\InvoiceModelFactory;
use App\Domain\Invoice\Factories\InvoiceRowModelFactory;
use App\Domain\Invoice\ValueObjects\InvoicePayload;

class CreateInvoiceService extends InvoiceService
{
    public function create(InvoicePayload $invoicePayload, int $customerId) : int
    {
        $progressive = $this->getInvoiceProgressiveFromPayload($invoicePayload);
        
        if (!empty($this->getInvoice($progressive)))
        {
            throw new InvoiceAlreadyExistsException("Invoice with progressive $progressive already exists!");
        }

        $invoiceId = $this->invoiceRepository->create(
            InvoiceModelFactory::create(
                null,
                $customerId,
                $progressive,
                $invoicePayload->getTotal()->getValue()
            ), 
        ); 

        $this->createInvoiceRows($invoicePayload, $invoiceId);

        return $invoiceId;
    }

    protected function createInvoiceRows(InvoicePayload $invoicePayload, int $invoiceId) : void
    {
        $this->invoiceRowsRepository->bulkCreate(
            $this->mapInvoiceRowsToModel($invoicePayload, $invoiceId)
        );
    }
}