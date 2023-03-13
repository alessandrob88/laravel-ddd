<?php
namespace Tests\Unit\Domain\Invoice\ValueObjects;

use App\Domain\Invoice\ValueObjects\InvoicePayload;
use App\Domain\Invoice\ValueObjects\Customer;
use App\Domain\Invoice\ValueObjects\InvoiceRow;
use App\Domain\Invoice\ValueObjects\Progressive;
use App\Domain\Invoice\ValueObjects\Total;
use PHPUnit\Framework\TestCase;

class InvoicePayloadTest extends TestCase
{
    public function testCanCreateFromValidArray()
    {
        $data = [
            'customer' => [
                'id' => 'd999461f-7b61-46c8-9a58-ca871738e816',
                'businessName' => 'Foo SRL',
                'vat' => '12345678901',
            ],
            'progressive' => 'INV-001',
            'total' => 0.0,
            'rows' => [
                [
                    'id' => '9309999b-4367-4377-9493-c7112dcc5ece',
                    'description' => 'Lorem ipsum dolor sit amet',
                    'total' => 100.0,
                    'quantity' => 1,
                ],
            ],
        ];

        $dto = InvoicePayload::fromArray($data);

        $this->assertInstanceOf(Customer::class, $dto->getCustomer());
        $this->assertEquals($data['customer']['id'], $dto->getCustomer()->getId());
        $this->assertEquals($data['customer']['businessName'], $dto->getCustomer()->getBusinessName());
        $this->assertEquals($data['customer']['vat'], $dto->getCustomer()->getVat());

        $this->assertInstanceOf(Progressive::class, $dto->getProgressive());
        $this->assertEquals($data['progressive'], $dto->getProgressive()->getProgressive());

        $this->assertInstanceOf(Total::class, $dto->getTotal());
        $this->assertEquals($data['total'], $dto->getTotal()->getTotal());

        $this->assertIsArray($dto->getRows());
        $this->assertCount(1, $dto->getRows());
        $this->assertInstanceOf(InvoiceRow::class, $dto->getRows()[0]);
        $this->assertEquals($data['rows'][0]['id'], $dto->getRows()[0]->getId());
        $this->assertEquals($data['rows'][0]['description'], $dto->getRows()[0]->getDescription());
        $this->assertEquals($data['rows'][0]['total'], $dto->getRows()[0]->getTotal());
        $this->assertEquals($data['rows'][0]['quantity'], $dto->getRows()[0]->getQuantity());
    }
}
