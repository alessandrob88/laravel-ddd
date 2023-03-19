<?php

namespace App\Domain\Invoice\Repositories;

use App\Domain\Invoice\Factories\InvoiceRowModelFactory;
use App\Domain\Invoice\Models\InvoiceRow;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\UuidInterface;

class InvoiceRowsRepository 
{
    private $table = 'invoice_rows';
    private $primaryKey = 'id';
    private $invoiceIdColumn = 'invoice_id';

    public function create(InvoiceRow $invoiceRow) : void
    {
        DB::table($this->table)->insert($invoiceRow->toArray());
    }

    public function bulkCreate(array $invoiceRows) : void
    {
        DB::table($this->table)->insert(array_map(function($row) {
            return $row->toArray();
        }, $invoiceRows));
    }
    
    public function read(UuidInterface $invoiceRowId)
    {
        $invoiceRowsCollection = DB::table($this->table)
            ->where($this->primaryKey, '=', $invoiceRowId)->get();

        if ($invoiceRowsCollection->count() === 0) 
        {
            return null;
        }

        return InvoiceRowModelFactory::fromArray($invoiceRowsCollection->first());
    }

    public function readByInvoiceId(int $invoiceId) : array
    {
        $invoiceRowsCollection = DB::table($this->table)
            ->where($this->invoiceIdColumn, '=', $invoiceId)->get();

        if ($invoiceRowsCollection->count() === 0) 
        {
            return null;
        }

        $invoiceRows = [];
        foreach($invoiceRowsCollection->all() as $invoiceRows) 
        {
            array_push($invoiceRows, InvoiceRowModelFactory::fromArray($invoiceRows));
        }

        return $invoiceRows;
    }

    public function bulkUpdate(array $invoiceRows) : void
    {
        $table = DB::table($this->table);
    
        /** @var /App/Domain/Invoice/Models/InvoiceRow $row */
        foreach ($invoiceRows as $row)
        {
            $table->where($this->primaryKey, '=', $row->getId())->update($row->toArray());
        }
    }

    public function bulkDelete(array $invoiceRows) : void
    {
        $table = DB::table($this->table);

        /** @var /App/Domain/Invoice/Models/InvoiceRow $row */
        foreach ($invoiceRows as $row)
        {
            $table->where($this->primaryKey, '=', $row->getId())->delete();
        }
    }
}