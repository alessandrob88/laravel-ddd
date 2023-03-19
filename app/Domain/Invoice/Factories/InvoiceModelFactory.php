<?php

namespace App\Domain\Invoice\Factories;

use App\Domain\Invoice\Models\Invoice;
use DateTimeImmutable;
use InvalidArgumentException;

class InvoiceModelFactory 
{
    public static function create(
        ?int $id,
        int $customerId,
        string $progressive,
        float $total,
        $createdAt = null,
        $updatedAt = null
    ) : Invoice
    {
        $createdAt = ($createdAt instanceof DateTimeImmutable) ? $createdAt : new DateTimeImmutable($createdAt ?? null);
        $updatedAt = ($updatedAt instanceof DateTimeImmutable) ? $updatedAt : new DateTimeImmutable($updatedAt ?? null);
        
        return new Invoice(
            $id,
            $customerId, 
            $progressive,
            $total,
            $createdAt,
            $updatedAt,
        );
    }

    public static function fromArray(array $data): Invoice
    {
        if (!isset($data['customer_id'], $data['progressive'], $data['total'])) {
            throw new InvalidArgumentException('Invalid data array for creating invoice');
        }

        $createdAt = ($data['created_at'] instanceof DateTimeImmutable) ? $data['created_at'] : new DateTimeImmutable($data['created_at'] ?? null);
        $updatedAt = ($data['updated_at'] instanceof DateTimeImmutable) ? $data['updated_at'] : new DateTimeImmutable($data['updated_at'] ?? null);
        
        return new Invoice(
            $data['id'] ?? null,
            $data['customer_id'],
            $data['progressive'],
            $data['total'],
            $createdAt,
            $updatedAt,
        );
    }
}