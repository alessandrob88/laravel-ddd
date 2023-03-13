<?php

namespace App\Domain\Invoice\ValueObjects;

final class Customer
{
    private string $id;
    private string $businessName;
    private string $vat;

    public function __construct(string $id, string $businessName, string $vat)
    {
        $this->id = $id;
        $this->businessName = $businessName;
        $this->vat = $vat;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['businessName'],
            $data['vat']
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBusinessName(): string
    {
        return $this->businessName;
    }

    public function getVat(): string
    {
        return $this->vat;
    }
}
