<?php
namespace App\Domain\Invoice\Factories;

use App\Domain\Invoice\Services\CreateInvoiceRowsService;

class CreateInvoiceRowServiceFactory 
{
    public static function create() : CreateInvoiceRowsService 
    {
        return new CreateInvoiceRowsService(
            InvoiceRepositoryFactory::create(),
            InvoiceRowsRepositoryFactory::create()
        );
    }
}