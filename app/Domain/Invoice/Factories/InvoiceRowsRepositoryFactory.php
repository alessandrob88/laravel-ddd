<?php
namespace App\Domain\Invoice\Factories;

use App\Domain\Invoice\Repositories\InvoiceRepository;
use App\Domain\Invoice\Repositories\InvoiceRowsRepository;

class InvoiceRowsRepositoryFactory 
{   
    public static function create() : InvoiceRowsRepository 
    {
        return new InvoiceRowsRepository();
    }
}