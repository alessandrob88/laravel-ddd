<?php
namespace Tests\Domain\Invoice\Services;

use PHPUnit\Framework\TestCase;
use App\Domain\Invoice\Services\DeleteInvoiceService;
use App\Domain\Invoice\Exceptions\InvoiceNotFoundException;
use App\Domain\Invoice\Models\Invoice;
use App\Domain\Invoice\Repositories\InvoiceRepository;
use App\Domain\Invoice\Repositories\InvoiceRowsRepository;
use App\Domain\Invoice\ValueObjects\InvoicePayload;
use Ramsey\Uuid\Uuid;

class DeleteInvoiceServiceTest extends TestCase
{
    private DeleteInvoiceService $deleteInvoiceService;
    private InvoicePayload $invoicePayload;
    private string $progressive;
    private array $rowIds;
    private InvoiceRepository $invoiceRepository;
    private InvoiceRowsRepository $invoiceRowsRepository;

    public function setUp(): void
    {
        $this->invoiceRepository = $this->createMock(InvoiceRepository::class);
        $this->invoiceRowsRepository = $this->createMock(InvoiceRowsRepository::class);
        $this->deleteInvoiceService = new DeleteInvoiceService($this->invoiceRepository, $this->invoiceRowsRepository);

        $this->progressive = 'INV-001';
        $this->rowIds = [Uuid::uuid4(), Uuid::uuid4()];
           
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
                    'id' => $this->rowIds[0],
                    'description' => 'foo bar foo',
                    'total' => 50.0,
                    'quantity' => 2,
                ],
                [
                    'id' => $this->rowIds[1],
                    'description' => 'foo bar foo bar',
                    'total' => 50.0,
                    'quantity' => 1,
                ],
            ]
        ]);
    }

    public function testDeleteInvoiceSuccessfully(): void
    {
        $this->invoiceRepository
            ->expects($this->once())
            ->method('read')
            ->willReturn($this->createMock(Invoice::class));

        $this->invoiceRepository
            ->expects($this->once())
            ->method('delete');
    
        $this->deleteInvoiceService->delete($this->invoicePayload);
    }

    public function testDeleteInvoiceNotFound(): void
    {
        $this->expectException(InvoiceNotFoundException::class);

        $this->invoiceRepository
            ->expects($this->once())
            ->method('read')
            ->willReturn(null);

        $this->deleteInvoiceService->delete($this->invoicePayload);
    }
}
