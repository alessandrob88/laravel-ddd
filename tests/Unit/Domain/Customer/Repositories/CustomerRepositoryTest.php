<?php
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Repositories\CustomerRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

final class CustomerRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private CustomerRepository $customerRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customerRepository = new CustomerRepository();
    }

    public function testItShouldCreateACustomer()
    {
        $uuid = Uuid::uuid4();
        $customer = new Customer(null, $uuid, 'Foo SRL', '123456789', new \DateTimeImmutable(), new \DateTimeImmutable());

        $id = $this->customerRepository->create($customer);

        $this->assertDatabaseHas('customers', [
            'id' => $id,
            'uuid' => $uuid,
            'business_name' => 'Foo SRL',
            'vat' => '123456789',
        ]);
    }

    public function testItShouldReturnACustomerByUuid()
    {
        $uuid = Uuid::uuid4();
        $customer = new Customer(null, $uuid, 'Bar Spa', '123456789', new \DateTimeImmutable(), new \DateTimeImmutable());
        $id = $this->customerRepository->create($customer);

        $result = $this->customerRepository->read($uuid->toString());

        $this->assertInstanceOf(Customer::class, $result);
        $this->assertEquals($id, $result->getId());
        $this->assertEquals($uuid, $result->getUuid());
        $this->assertEquals('Bar Spa', $result->getBusinessName());
        $this->assertEquals('123456789', $result->getVat());
    }

    public function testItShouldReturnNullWhenCustomerDoesNotExist()
    {
        $result = $this->customerRepository->read(Uuid::uuid4()->toString());
        $this->assertNull($result);
    }
}
