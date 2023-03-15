<?php

namespace App\Domain\Invoice\Services;

use App\Domain\Customer\ValueObjects\Customer;
use App\Domain\Invoice\Models\Invoice;
use App\Domain\Invoice\Models\InvoiceRow;
use App\Domain\Invoice\Repositories\InvoiceRepository;
use App\Domain\Invoice\ValueObjects\InvoicePayload;
use App\Repositories\CustomerRepository;
use Exception;

class CreateInvoiceService {

    public function __construct(
        private InvoiceRepository $invoiceRepository,
    )
    {}

    public function create(InvoicePayload $invoicePayload, int $customerId) : void
    {
        $progressive = $invoicePayload->getProgressive()->getProgressive();

        $invoice = $this->invoiceRepository->getInvoiceHead($progressive);

        if ($invoice) {
            throw new Exception("Invoice with progressive $progressive already exists!");
        }

        $this->invoiceRepository->create(
            $this->createInvoiceHead($invoicePayload, $customerId), 
            $this->createInvoiceRows($invoicePayload),
        );
    }

    private function createInvoiceHead(InvoicePayload $invoicePayload, int $customerId) {
        return Invoice::fromArray([
            'customer_id' => $customerId,
            'progressive' => $invoicePayload->getProgressive()->getProgressive(),
            'total' => $invoicePayload->getTotal()->getTotal(),
        ]);
    }

    private function createInvoiceRows(InvoicePayload $invoicePayload) {
        $invoiceRows = [];
        foreach($invoicePayload->getRows() as $row) {
            array_push($invoiceRows, InvoiceRow::fromArray([
                'description' => $row->getDescription(),
                'total' => $row->getTotal(),
                'quantity' => $row->getQuantity(),
            ]));
        }
        return $invoiceRows;
    }
}