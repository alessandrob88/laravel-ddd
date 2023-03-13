<?php
namespace App\Domain\Invoice\ValueObjects;

final class Total
{
    private float $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    public static function fromArray(array $data): self
    {
        return new self((float) $data['total']);
    }

    public function getTotal(): float
    {
        return $this->value;
    }
}