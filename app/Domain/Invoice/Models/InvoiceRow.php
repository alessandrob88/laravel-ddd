<?php
namespace App\Domain\Invoice\Models;

use \DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

class InvoiceRow
{
    public function __construct(
        private ?UuidInterface $id,
        private ?int $invoice_id,
        private string $description,
        private float $total,
        private int $quantity,
        private DateTimeImmutable $created_at,
        private DateTimeImmutable $updated_at,
        private ?string $event = null,
    ) {}

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getInvoiceId(): int
    {
        return $this->invoice_id;
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

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function getEvent() : ?string
    {
        return $this->event;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'description' => $this->description,
            'total' => $this->total,
            'quantity' => $this->quantity,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
