<?php
namespace Tests\Domain\Invoice\Services;

use App\Domain\Invoice\Exceptions\InvoiceAlreadyExistsException;
use App\Domain\Invoice\Models\Invoice;
use App\Domain\Invoice\Repositories\InvoiceRepository;
use App\Domain\Invoice\Repositories\InvoiceRowsRepository;
use App\Domain\Invoice\Services\CreateInvoiceService;
use App\Domain\Invoice\ValueObjects\InvoicePayload;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CreateInvoiceServiceTest extends TestCase
{
    private CreateInvoiceService $service;
    private InvoiceRepository $invoiceRepository;
    private InvoiceRowsRepository $invoiceRowsRepository;

    public function setUp(): void
    {
        $this->invoiceRepository = $this->createMock(InvoiceRepository::class);
        $this->invoiceRowsRepository = $this->createMock(InvoiceRowsRepository::class);
        $this->service = new CreateInvoiceService($this->invoiceRepository, $this->invoiceRowsRepository);
    }

    public function testCreateInvoice()
    {
        $progressive = 'INV-001';
        $rowIds = [
            Uuid::uuid4(),
            Uuid::uuid4(),
        ];
        $invoicePayload = InvoicePayload::fromArray([
            'customer' => [
                'id' => 'd999461f-7b61-46c8-9a58-ca871738e816',
                'businessName' => 'Foo SRL',
                'vat' => '12345678901',
            ],
            'progressive' => $progressive,
            'total' => 100.0,
            'rows' => [
                [
                    'id' => $rowIds[0],
                    'description' => 'foo bar foo',
                    'total' => 50.0,
                    'quantity' => 2,
                ],
                [
                    'id' => $rowIds[1],
                    'description' => 'foo bar foo bar',
                    'total' => 50.0,
                    'quantity' => 1,
                ],
            ]
        ]);

        $invoiceId = 1;
        $customerId = 1;

        $this->invoiceRepository
            ->expects($this->once())
            ->method('read')
            ->with($progressive)
            ->willReturn(null);

        $this->invoiceRepository
            ->expects($this->once())
            ->method('create')
            ->willReturn($invoiceId);

        $this->invoiceRowsRepository
            ->expects($this->once())
            ->method('bulkCreate');

        $result = $this->service->create($invoicePayload, $customerId);

        $this->assertEquals($invoiceId, $result);
    }

    public function testCreateInvoiceWithExistingProgressive()
    {
        $customerId = 1;
        $progressive = 'INV-001';
        $rowIds = [
            Uuid::uuid4(),
            Uuid::uuid4(),
        ];
        $invoicePayload = InvoicePayload::fromArray([
            'customer' => [
                'id' => 'd999461f-7b61-46c8-9a58-ca871738e816',
                'businessName' => 'Foo SRL',
                'vat' => '12345678901',
            ],
            'progressive' => $progressive,
            'total' => 100.0,
            'rows' => [
                [
                    'id' => $rowIds[0],
                    'description' => 'foo bar foo',
                    'total' => 50.0,
                    'quantity' => 2,
                ],
                [
                    'id' => $rowIds[1],
                    'description' => 'foo bar foo bar',
                    'total' => 50.0,
                    'quantity' => 1,
                ],
            ]
        ]);

        $existingInvoice = new Invoice(null, $customerId, $progressive, 100.0, null, null);

        $this->invoiceRepository
            ->expects($this->once())
            ->method('read')
            ->with($progressive)
            ->willReturn($existingInvoice);

        $this->expectException(InvoiceAlreadyExistsException::class);
        $this->service->create($invoicePayload, $customerId);
    }
}
