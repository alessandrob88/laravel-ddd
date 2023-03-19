<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Invoice\Services\UpdateInvoiceRowsService;
use App\Domain\Invoice\Repositories\InvoiceRepository;
use App\Domain\Invoice\Repositories\InvoiceRowsRepository;
use App\Domain\Invoice\Exceptions\InvoiceNotFoundException;
use App\Domain\Invoice\Factories\InvoiceModelFactory;
use App\Domain\Invoice\Factories\InvoiceRowModelFactory;
use App\Domain\Invoice\ValueObjects\InvoicePayload;
use App\Domain\Invoice\Models\InvoiceRow;
use Ramsey\Uuid\Uuid;

class UpdateInvoiceRowsServiceTest extends TestCase
{
    private InvoiceRepository $invoiceRepository;
    private InvoiceRowsRepository $invoiceRowsRepository;
    private UpdateInvoiceRowsService $updateInvoiceRowsService;
    private InvoicePayload $invoicePayload;
    private string $progressive;

    public function setUp(): void
    {
        $this->invoiceRowsRepository = $this->createMock(InvoiceRowsRepository::class);
        $this->invoiceRepository = $this->createMock(InvoiceRepository::class);
        $this->updateInvoiceRowsService = new UpdateInvoiceRowsService(
            $this->invoiceRepository,
            $this->invoiceRowsRepository
        );
        $this->progressive = 'INV-001';
        $this->invoicePayload = InvoicePayload::fromArray([
            'customer' => [
                'id' => 'd999461f-7b61-46c8-9a58-ca871738e816',
                'businessName' => 'Foo SRL',
                'vat' => '12345678901',
            ],
            'progressive' => $this->progressive,
            'total' => 100.0,
            'rows' => [
                [
                    'id' => Uuid::uuid4(),
                    'description' => 'foo bar foo',
                    'total' => 50.0,
                    'quantity' => 2,
                    'event' => 'update',
                ],
                [
                    'id' => Uuid::uuid4(),
                    'description' => 'foo bar foo bar',
                    'total' => 50.0,
                    'quantity' => 1,
                    'event' => 'delete',
                ],
            ]
        ]);
    }

    public function testUpdateWithExistingInvoice()
    {
        $invoice = InvoiceModelFactory::create(1, 1, $this->progressive, 100.00);

        $this->invoiceRepository
            ->expects($this->once())
            ->method('read')
            ->with($this->progressive)
            ->willReturn($invoice);

        $this->invoiceRepository
            ->expects($this->never())
            ->method('create');

        $this->invoiceRowsRepository
            ->expects($this->once())
            ->method('bulkUpdate');

        $this->invoiceRowsRepository
            ->expects($this->once())
            ->method('bulkDelete');

        $this->updateInvoiceRowsService->update($this->invoicePayload);
    }

    public function testUpdateWithNonExistingInvoice()
    {
        $this->expectException(InvoiceNotFoundException::class);

        $this->invoiceRepository
            ->expects($this->once())
            ->method('read')
            ->with($this->invoicePayload->getProgressive()->getValue())
            ->willReturn(null);

        $this->updateInvoiceRowsService->update($this->invoicePayload);
    }
}
