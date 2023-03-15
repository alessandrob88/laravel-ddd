<?php

namespace App\Domain\Customer\ValueObjects;

final class Customer
{
    public function __construct(
        private string $id,
        private string $businessName,
        private string $vat,
    )
    {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['businessName'],
            $data['vat']
        );
    }

    public function toArray() {
        return [
            'id' => $this->getId(),
            'businessName' => $this->getBusinessName(),
            'vat' => $this->getVat(),
        ];
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
