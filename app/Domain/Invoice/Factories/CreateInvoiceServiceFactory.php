<?php
namespace App\Domain\Invoice\Factories;

use App\Domain\Invoice\Services\CreateInvoiceService;

class CreateInvoiceServiceFactory 
{
    public static function create() : CreateInvoiceService 
    {
        return new CreateInvoiceService(
            InvoiceRepositoryFactory::create(),
            InvoiceRowsRepositoryFactory::create()
        );
    }
}