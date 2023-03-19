<?php

namespace App\Domain\Invoice\Services;

use App\Domain\Invoice\Factories\InvoiceRowModelFactory;
use App\Domain\Invoice\Models\Invoice;
use App\Domain\Invoice\Repositories\InvoiceRepository;
use App\Domain\Invoice\Repositories\InvoiceRowsRepository;
use App\Domain\Invoice\ValueObjects\InvoicePayload;

abstract class InvoiceService
{
    public function __construct(
        protected InvoiceRepository $invoiceRepository,
        protected InvoiceRowsRepository $invoiceRowsRepository,
    )
    {}

    protected function getInvoice(string $progressive) : ?Invoice
    {
        return $this->invoiceRepository->read($progressive);
    }

    protected function getInvoiceProgressiveFromPayload(InvoicePayload $invoicePayload) : string
    {
        return $invoicePayload->getProgressive()->getValue();
    }

    protected function mapInvoiceRowsToModel(InvoicePayload $invoicePayload, int $invoiceId) : array
    {
        $invoiceRows = [];
        if(count($invoicePayload->getRows()) > 0)
        {
            foreach($invoicePayload->getRows() as $invoiceRow)
            {
                $invoiceRows[] = InvoiceRowModelFactory::create(
                    $invoiceRow->getId(),
                    $invoiceId,
                    $invoiceRow->getDescription(),
                    $invoiceRow->getTotal(),
                    $invoiceRow->getQuantity(),
                    null,
                    null,
                    $invoiceRow->getEvent(),
                );
            }
        }
        return $invoiceRows;
    }
}