<?php
namespace App\Domain\Invoice\Factories;

use App\Domain\Invoice\Repositories\InvoiceRepository;

class InvoiceRepositoryFactory 
{   
    public static function create() : InvoiceRepository 
    {
        return new InvoiceRepository();
    }
}