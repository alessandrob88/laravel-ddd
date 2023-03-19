<?php

namespace App\Domain\Invoice\Factories;

use App\Domain\Invoice\Services\UpdateInvoiceRowsService;

class UpdateInvoiceRowsServiceFactory 
{
    public static function create() : UpdateInvoiceRowsService 
    {
        return new UpdateInvoiceRowsService(
            InvoiceRepositoryFactory::create(),
            InvoiceRowsRepositoryFactory::create(),
        );
    }
}