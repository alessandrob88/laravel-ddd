<?php
namespace App\Domain\Invoice\Factories;

use App\Domain\Invoice\Services\DeleteInvoiceService;

class DeleteInvoiceServiceFactory 
{
    public static function create() : DeleteInvoiceService 
    {
        return new DeleteInvoiceService(
            InvoiceRepositoryFactory::create(),
            InvoiceRowsRepositoryFactory::create()
        );
    }
}