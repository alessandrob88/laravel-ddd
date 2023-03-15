<?php
namespace App\Domain\Invoice\Models;

use InvalidArgumentException;
use \DateTimeImmutable;

class Invoice
{
    public function __construct(
        private ?string $id,
        private string $customerId,
        private string $progressive,
        private float $total,
        private \DateTimeImmutable $createdAt,
        private \DateTimeImmutable $updatedAt,
    ) {}

    public static function fromArray(array $data): self
    {
        if (!isset($data['customer_id'], $data['progressive'], $data['total'])) {
            throw new InvalidArgumentException('Invalid data array for creating invoice');
        }

        return new self(
            $data['id'] ?? null,
            $data['customer_id'],
            $data['progressive'],
            $data['total'],
            new DateTimeImmutable($data['created_at'] ?? null),
            new DateTimeImmutable($data['updated_at'] ?? null),
        );
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

    public function getId(): string
    {
        return $this->id;
    }


    public function getCustomerId(): string
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
}
