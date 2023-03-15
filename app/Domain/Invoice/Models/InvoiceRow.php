<?php
namespace App\Domain\Invoice\Models;

use \DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

class InvoiceRow
{
    public function __construct(
        private ?UuidInterface $id,
        private int $invoice_id,
        private string $description,
        private float $total,
        private int $quantity,
        private DateTimeImmutable $created_at,
        private DateTimeImmutable $updated_at
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

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['id']) ? \Ramsey\Uuid\Uuid::fromString($data['id']) : null,
            $data['invoice_id'],
            $data['description'],
            $data['total'],
            $data['quantity'],
            new DateTimeImmutable($data['created_at'] ?? null),
            new DateTimeImmutable($data['updated_at'] ?? null)
        );
    }
}
