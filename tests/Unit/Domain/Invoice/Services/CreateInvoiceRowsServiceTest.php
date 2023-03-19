<?php
namespace Tests\Domain\Invoice\Services;

use PHPUnit\Framework\TestCase;
use App\Domain\Invoice\Factories\InvoiceModelFactory;
use App\Domain\Invoice\Repositories\InvoiceRepository;
use App\Domain\Invoice\Services\CreateInvoiceRowsService;
use App\Domain\Invoice\ValueObjects\InvoicePayload;
use App\Domain\Invoice\Exceptions\InvoiceNotFoundException;
use App\Domain\Invoice\Repositories\InvoiceRowsRepository;
use Ramsey\Uuid\Uuid;

class CreateInvoiceRowsServiceTest extends TestCase
{
    private InvoiceRepository $invoiceRepositoryMock;
    private InvoiceRowsRepository $invoiceRowsRepositoryMock;
    private CreateInvoiceRowsService $createInvoiceRowsService;
    private InvoicePayload $invoicePayload;
    private string $progressive;
    
    public function setUp(): void
    {
        $this->invoiceRepositoryMock = $this->createMock(InvoiceRepository::class);
        $this->invoiceRowsRepositoryMock = $this->createMock(InvoiceRowsRepository::class);
        $this->createInvoiceRowsService = new CreateInvoiceRowsService(
            $this->invoiceRepositoryMock,
            $this->invoiceRowsRepositoryMock,
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
                ],
                [
                    'id' => Uuid::uuid4(),
                    'description' => 'foo bar foo bar',
                    'total' => 50.0,
                    'quantity' => 1,
                ],
            ]
        ]);
    }
    
    public function testCreateRowsThrowsExceptionIfInvoiceDoesNotExist()
    {
        $this->invoiceRepositoryMock
            ->method('read')
            ->with($this->progressive)
            ->willReturn(null);
        
        $this->expectException(InvoiceNotFoundException::class);
        
        $this->createInvoiceRowsService->createRows($this->invoicePayload);
    }
    
    public function testCreateRowsUpdatesInvoiceTotal()
    {
        $invoice = InvoiceModelFactory::create(1, 1, $this->progressive, 100.00);
        $invoicePayload = InvoicePayload::fromArray([
            'customer' => [
                'id' => 'd999461f-7b61-46c8-9a58-ca871738e816',
                'businessName' => 'Foo SRL',
                'vat' => '12345678901',
            ],
            'progressive' => $this->progressive,
            'total' => 150.0,
            'rows' => [
                [
                    'id' => Uuid::uuid4(),
                    'description' => 'new foo bar foo',
                    'total' => 50.0,
                    'quantity' => 2,
                ],
            ]
        ]);
        
        $this->invoiceRepositoryMock
            ->method('read')
            ->with($this->progressive)
            ->willReturn($invoice);
        
        $this->invoiceRepositoryMock
            ->expects($this->once())
            ->method('update')
            ->with(
                $this->callback(function ($updatedInvoice) use ($invoice, $invoicePayload) {
                    return $updatedInvoice->getId() === $invoice->getId() &&
                        $updatedInvoice->getCustomerId() === $invoice->getCustomerId() &&
                        $updatedInvoice->getProgressive() === $invoice->getProgressive() &&
                        $updatedInvoice->getTotal() === $invoicePayload->getTotal()->getValue();
                })
            );
        
        $this->createInvoiceRowsService->createRows($invoicePayload);
    }
}
