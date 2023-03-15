<?php

namespace App\Domain\Customer\Models;

use Ramsey\Uuid\UuidInterface;

class Customer
{
    public function __construct(
        private ?int $id,
        private UuidInterface $uuid,
        private string $businessName,
        private string $vat,
        private \DateTimeImmutable $createdAt,
        private \DateTimeImmutable $updatedAt
    )
    {}

    public function getId() : ?int 
    {
        return $this->id;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getBusinessName(): string
    {
        return $this->businessName;
    }

    public function getVat(): string
    {
        return $this->vat;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'business_name' => $this->businessName,
            'vat' => $this->vat,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
