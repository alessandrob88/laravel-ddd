<?php
namespace App\Domain\Invoice\Factories;

use App\Domain\Invoice\Repositories\InvoiceRepository;
use App\Domain\Invoice\Services\CreateInvoiceService;

class CreateInvoiceServiceFactory 
{
    public static function create (InvoiceRepository $invoiceRepository) : CreateInvoiceService 
    {
        return new CreateInvoiceService($invoiceRepository);
    }
}