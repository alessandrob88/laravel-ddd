<?php
namespace Tests\Unit\Domain\Invoice\ValueObjects;

use PHPUnit\Framework\TestCase;
use App\Domain\Invoice\ValueObjects\InvoiceRow;

class InvoiceRowTest extends TestCase
{
    public function testCreateFromDataArray(): void
    {
        $data = [
            'id' => '9309999b-4367-4377-9493-c7112dcc5ece',
            'description' => 'Lorem ipsum dolor sit amet',
            'total' => 100.00,
            'quantity' => 1,
        ];

        $row = InvoiceRow::fromArray($data);

        $this->assertInstanceOf(InvoiceRow::class, $row);
        $this->assertEquals($data['id'], $row->getId());
        $this->assertEquals($data['description'], $row->getDescription());
        $this->assertEquals($data['total'], $row->getTotal());
        $this->assertEquals($data['quantity'], $row->getQuantity());
    }

    public function testCanGetProperties(): void
    {
        $id = '9309999b-4367-4377-9493-c7112dcc5ece';
        $description = 'Lorem ipsum dolor sit amet';
        $total = 100.00;
        $quantity = 1;

        $row = new InvoiceRow($id, $description, $total, $quantity);

        $this->assertInstanceOf(InvoiceRow::class, $row);
        $this->assertEquals($id, $row->getId());
        $this->assertEquals($description, $row->getDescription());
        $this->assertEquals($total, $row->getTotal());
        $this->assertEquals($quantity, $row->getQuantity());
    }
}
