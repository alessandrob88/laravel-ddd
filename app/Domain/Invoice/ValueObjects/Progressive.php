<?php 
namespace App\Domain\Invoice\ValueObjects;

final class Progressive
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromArray(array $data): self
    {
        return new self($data['progressive']);
    }

    public function getProgressive(): string
    {
        return $this->value;
    }
}