<?php
namespace Tests\Unit\Domain\Invoice\ValueObjects;

use App\Domain\Invoice\ValueObjects\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    public function testCanGetProperties()
    {
        $id = 'd999461f-7b61-46c8-9a58-ca871738e816';
        $businessName = 'Foo SRL';
        $vat = '12345678901';

        $customer = new Customer($id, $businessName, $vat);

        $this->assertEquals($id, $customer->getId());
        $this->assertEquals($businessName, $customer->getBusinessName());
        $this->assertEquals($vat, $customer->getVat());
    }

    public function testCanCreateFromDataArray()
    {
        $data = [
            'id' => 'd999461f-7b61-46c8-9a58-ca871738e816',
            'businessName' => 'Foo SRL',
            'vat' => '12345678901',
        ];

        $customer = Customer::fromArray($data);

        $this->assertEquals($data['id'], $customer->getId());
        $this->assertEquals($data['businessName'], $customer->getBusinessName());
        $this->assertEquals($data['vat'], $customer->getVat());
    }
}
