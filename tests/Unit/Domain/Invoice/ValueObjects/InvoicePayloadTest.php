<?php
namespace Tests\Unit\Domain\Invoice\ValueObjects;

use App\Domain\Invoice\ValueObjects\InvoicePayload;
use App\Domain\Customer\ValueObjects\Customer;
use App\Domain\Invoice\ValueObjects\InvoiceRow;
use App\Domain\Invoice\ValueObjects\Progressive;
use App\Domain\Invoice\ValueObjects\Total;
use PHPUnit\Framework\TestCase;

final class InvoicePayloadTest extends TestCase
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
                    'event' => 'create',
                ],
            ],
        ];

        $invoicePayload = InvoicePayload::fromArray($data);

        $this->assertInstanceOf(Customer::class, $invoicePayload->getCustomer());
        $this->assertEquals($data['customer']['id'], $invoicePayload->getCustomer()->getId());
        $this->assertEquals($data['customer']['businessName'], $invoicePayload->getCustomer()->getBusinessName());
        $this->assertEquals($data['customer']['vat'], $invoicePayload->getCustomer()->getVat());

        $this->assertInstanceOf(Progressive::class, $invoicePayload->getProgressive());
        $this->assertEquals($data['progressive'], $invoicePayload->getProgressive()->getValue());

        $this->assertInstanceOf(Total::class, $invoicePayload->getTotal());
        $this->assertEquals($data['total'], $invoicePayload->getTotal()->getValue());

        $this->assertIsArray($invoicePayload->getRows());
        $this->assertCount(1, $invoicePayload->getRows());
        $this->assertInstanceOf(InvoiceRow::class, $invoicePayload->getRows()[0]);
        $this->assertEquals($data['rows'][0]['id'], $invoicePayload->getRows()[0]->getId());
        $this->assertEquals($data['rows'][0]['description'], $invoicePayload->getRows()[0]->getDescription());
        $this->assertEquals($data['rows'][0]['total'], $invoicePayload->getRows()[0]->getTotal());
        $this->assertEquals($data['rows'][0]['quantity'], $invoicePayload->getRows()[0]->getQuantity());
        $this->assertEquals($data['rows'][0]['event'], $invoicePayload->getRows()[0]->getEvent());
    }
}
