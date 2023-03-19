<?php
namespace App\Domain\Invoice\ValueObjects;

final class Total
{
    public function __construct(
        private float $value)
    {}

    public static function fromArray(array $data): self
    {
        return new self((float) $data['total']);
    }

    public function getValue(): float
    {
        return $this->value;
    }
}