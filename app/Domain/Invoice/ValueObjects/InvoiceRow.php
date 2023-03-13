<?php

namespace App\Domain\Invoice\ValueObjects;

final class InvoiceRow
{
    private string $id;
    private string $description;
    private float $total;
    private int $quantity;

    public function __construct(string $id, string $description, float $total, int $quantity)
    {
        $this->id = $id;
        $this->description = $description;
        $this->total = $total;
        $this->quantity = $quantity;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['description'],
            (float) $data['total'],
            (int) $data['quantity'],
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
}
