<?php

namespace App\Domain\Invoice\ValueObjects;

final class InvoiceRow
{
    public function __construct(
        private ?string $id, 
        private string $description, 
        private float $total, 
        private int $quantity,
        private ?string $event = null,
    )
    {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['description'],
            (float) $data['total'],
            (int) $data['quantity'],
            $data['event'] ?? null,
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getEvent(): ?string
    {
        return $this->event;
    }
}
