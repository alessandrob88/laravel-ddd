<?php
namespace App\Domain\Invoice\ValueObjects;

use App\Domain\Invoice\ValueObjects\Customer;
use App\Domain\Invoice\ValueObjects\InvoiceRow;
use App\Domain\Invoice\ValueObjects\Progressive;
use App\Domain\Invoice\ValueObjects\Total;

final class InvoicePayload
{
    private Customer $customer;
    private Progressive $progressive;
    private Total $total;
    private array $rows;

    public function __construct(Customer $customer, Progressive $progressive, Total $total, array $rows)
    {
        $this->customer = $customer;
        $this->progressive = $progressive;
        $this->total = $total;
        $this->rows = $rows;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getProgressive(): Progressive
    {
        return $this->progressive;
    }

    public function getTotal(): Total
    {
        return $this->total;
    }

    public function getRows(): array
    {
        return $this->rows;
    }

    public static function fromArray(array $data): self
    {
        $customer = Customer::fromArray($data['customer']);

        $progressive = Progressive::fromArray($data);

        $total = Total::fromArray($data);

        $rows = array_map(function ($row) {
            return InvoiceRow::fromArray($row);
        }, $data['rows']);

        return new self($customer, $progressive, $total, $rows);
    }

    public function toArray(): array
    {
        $rows = array_map(function ($row) {
            return $row->toArray();
        }, $this->rows);

        return [
            'customer' => [
                'id' => $this->customer->getId(),
                'businessName' => $this->customer->getBusinessName(),
                'vat' => $this->customer->getVat(),
            ],
            'progressive' => $this->progressive,
            'total' => $this->total->getTotal(),
            'rows' => $rows,
        ];
    }
}
