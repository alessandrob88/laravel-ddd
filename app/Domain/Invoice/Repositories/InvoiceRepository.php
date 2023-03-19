<?php

namespace App\Domain\Invoice\Repositories;

use App\Domain\Invoice\Factories\InvoiceModelFactory;
use App\Domain\Invoice\Models\{Invoice, InvoiceRow};
use Illuminate\Support\Facades\DB;

class InvoiceRepository
{
    private $table = 'invoices';
    private $progressiveColumn = 'progressive';
    private $primaryKey = 'id';

    public function create(Invoice $invoice) : int
    {
        return DB::table($this->table)->insertGetId($invoice->toArray());
    }
    
    public function read(string $progressive) : ?Invoice
    {
        $invoice = DB::table($this->table)
            ->where($this->progressiveColumn, '=', $progressive)
            ->get()
            ->first();
        
        if(empty($invoice))
        {
            return null;
        }

        return InvoiceModelFactory::fromArray((array) $invoice);
    }

    public function update(Invoice $invoice) : int 
    {
        return DB::table($this->table)
            ->where($this->primaryKey, '=', $invoice->getId())
            ->update($invoice->toArray());
    }

    public function delete(string $progressive) : int
    {
        return DB::table($this->table)
            ->where($this->progressiveColumn, '=', $progressive)
            ->delete();
    }
}
