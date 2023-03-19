<?php

namespace App\Domain\Invoice\Factories;

use App\Domain\Invoice\Models\InvoiceRow;
use Ramsey\Uuid\UuidInterface;
use DateTimeImmutable;

class InvoiceRowModelFactory 
{
    public static function create(
        ?string $id,
        int $invoiceId,
        string $description,
        float $total,
        int $quantity,
        $createdAt = null,
        $updatedAt = null,
        ?string $event
    ) : InvoiceRow
    {
        return new InvoiceRow(
            \Ramsey\Uuid\Uuid::fromString($id),
            $invoiceId,
            $description,
            $total,
            $quantity,
            new DateTimeImmutable($createdAt ?? null),
            new DateTimeImmutable($updatedAt ?? null),
            $event,
        );
    }

    public static function fromArray(array $data): InvoiceRow
    {
        return new InvoiceRow(
            isset($data['id']) ? \Ramsey\Uuid\Uuid::fromString($data['id']) : null,
            $data['invoice_id'] ?? null,
            $data['description'],
            $data['total'],
            $data['quantity'],
            new DateTimeImmutable($data['created_at'] ?? null),
            new DateTimeImmutable($data['updated_at'] ?? null)
        );
    }
}