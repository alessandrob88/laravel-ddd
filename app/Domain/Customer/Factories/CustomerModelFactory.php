<?php

namespace App\Domain\Customer\Factories;

use App\Domain\Customer\Models\Customer;

class CustomerModelFactory {

    public static function create(?int $id, string $uuid, string $businessName, string $vatCode, ?string $createdAt = null, ?string $updatedAt = null): Customer
    {
        return new Customer(
            $id ?? null,
            isset($uuid) ? \Ramsey\Uuid\Uuid::fromString($uuid) : null, 
            $businessName, 
            $vatCode, 
            new \DateTimeImmutable($createdAt), 
            new \DateTimeImmutable($updatedAt),
        );
    }
}