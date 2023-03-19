<?php
namespace App\Domain\Invoice\Models;

class Invoice
{
    public function __construct(
        private ?int $id,
        private int $customerId,
        private string $progressive,
        private float $total,
        private ?\DateTimeImmutable $createdAt,
        private ?\DateTimeImmutable $updatedAt,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }


    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function getProgressive(): string
    {
        return $this->progressive;
    }

    public function getTotal(): float
    {
        return $this->total;
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
            'customer_id' => $this->customerId,
            'progressive' => $this->progressive,
            'total' => $this->total,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
