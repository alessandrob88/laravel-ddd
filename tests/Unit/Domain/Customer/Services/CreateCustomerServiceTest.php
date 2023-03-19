<?php
use PHPUnit\Framework\TestCase;
use App\Domain\Customer\Services\CreateCustomerService;
use App\Domain\Customer\Repositories\CustomerRepository;
use App\Domain\Customer\Exceptions\CustomerAlreadyExistsException;
use App\Domain\Customer\Models\Customer as ModelsCustomer;
use App\Domain\Customer\ValueObjects\Customer;
use Ramsey\Uuid\Uuid;

class CreateCustomerServiceTest extends TestCase
{
    private CreateCustomerService $service;
    private CustomerRepository $repository;
    private ModelsCustomer $existingCustomer;
    private Customer $customerMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(CustomerRepository::class);
        $this->service = new CreateCustomerService($this->repository);
        $this->existingCustomer = new ModelsCustomer(1, Uuid::uuid4(), 'My Business', '12345678901', new DateTimeImmutable(), new DateTimeImmutable());
        $this->customerMock = new Customer(Uuid::uuid4(), 'My Business', '12345678901');
    }

    public function testCreateCustomerSuccessfully()
    {
        $this->repository->expects($this->once())
            ->method('read')
            ->willReturn(null);
        $this->repository->expects($this->once())
            ->method('create')
            ->willReturn(1);

        $result = $this->service->create($this->customerMock);

        $this->assertEquals($result, 1);
    }

    public function testCreateCustomerAlreadyExists()
    {
        $uuid = Uuid::uuid4();
        
        $this->repository->expects($this->once())
            ->method('read')
            ->willReturn($this->existingCustomer);

        $this->expectException(CustomerAlreadyExistsException::class);
        $this->expectExceptionMessage("Customer with uuid: {$this->existingCustomer->getUuid()} already exists!");

        $this->service->create($this->customerMock);
    }

    public function testCreateCustomerWithSameBusinessName()
    {
        $this->repository->expects($this->once())
            ->method('read')
            ->willReturn($this->existingCustomer);

        $this->expectException(CustomerAlreadyExistsException::class);
        $this->expectExceptionMessage("Customer with uuid: {$this->existingCustomer->getUuid()} already exists!");

        $this->service->create($this->customerMock);
    }

    public function testCreateCustomerWithSameVat()
    {
        $uuid = Uuid::uuid4();
        $this->repository->expects($this->once())
            ->method('read')
            ->willReturn($this->existingCustomer);

        $this->expectException(CustomerAlreadyExistsException::class);
        $this->expectExceptionMessage("Customer with uuid: {$this->existingCustomer->getUuid()} already exists!");

        $this->service->create($this->customerMock);
    }
}
