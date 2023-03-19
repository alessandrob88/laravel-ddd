<?php

use App\Domain\Invoice\Models\Invoice;
use App\Domain\Invoice\Repositories\InvoiceRepository;
use Database\Seeders\CustomersTableSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class InvoiceRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private InvoiceRepository $repository;
    private CustomersTableSeeder $customerSeeder;
    private array $customers;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new InvoiceRepository();
        $this->customers = (new CustomersTableSeeder())->run(2);
    }

    public function testItCanCreateAnInvoice(): void
    {
        $invoice = new Invoice(null, $this->customers[0]['id'], 'INV-001', 100.00, new DateTimeImmutable(), new DateTimeImmutable());

        $result = $this->repository->create($invoice);

        $this->assertGreaterThan(0, $result);
    }

    public function testItCanReadAnInvoice(): void
    {
        $invoice = new Invoice(null, $this->customers[1]['id'], 'INV-002', 200.00, new DateTimeImmutable(), new DateTimeImmutable());
        $id = $this->repository->create($invoice);

        $result = $this->repository->read('INV-002');

        $this->assertEquals($id, $result->getId());
        $this->assertEquals($this->customers[1]['id'], $result->getCustomerId());
        $this->assertEquals('INV-002', $result->getProgressive());
        $this->assertEquals(200.00, $result->getTotal());
        $this->assertNotNull($result->getCreatedAt());
        $this->assertNotNull($result->getUpdatedAt());
    }

    public function testItCanUpdateAnInvoice(): void
    {
        $invoice = new Invoice(null, $this->customers[0]['id'], 'INV-003', 300.00, new DateTimeImmutable(), new DateTimeImmutable());
        $id = $this->repository->create($invoice);

        $updatedInvoice = new Invoice($id, $this->customers[1]['id'], 'INV-003', 400.00, new DateTimeImmutable(), new DateTimeImmutable());
        $result = $this->repository->update($updatedInvoice);

        $this->assertEquals(1, $result);
    }

    public function testItCanDeleteAnInvoice(): void
    {
        $invoice = new Invoice(null, $this->customers[0]['id'], 'INV-004', 500.00, new DateTimeImmutable(), new DateTimeImmutable());
        $id = $this->repository->create($invoice);

        $result = $this->repository->delete('INV-004');

        $this->assertEquals(1, $result);
    }
}
