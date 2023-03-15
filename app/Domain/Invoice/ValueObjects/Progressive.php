<?php 
namespace App\Domain\Invoice\ValueObjects;

final class Progressive
{
    public function __construct(
        private string $value)
    {}

    public static function fromArray(array $data): self
    {
        return new self($data['progressive']);
    }

    public function getProgressive(): string
    {
        return $this->value;
    }
}