<?php

namespace App\Domain\Customer\Repositories;

use App\Domain\Customer\Factories\CustomerModelFactory;
use App\Domain\Customer\Models\Customer;
use Illuminate\Support\Facades\DB;
use Exception;

class CustomerRepository
{
    private $table = 'customers';

    public function getByUuid(string $uuid) : ?Customer
    {
        $record = DB::table($this->table)->where('uuid', $uuid)->first();
        
        if (!$record)
        {
            return null;
        }

        return CustomerModelFactory::create(
            $record->id,
            $record->uuid,
            $record->business_name,
            $record->vat, 
            $record->created_at,
            $record->updated_at,
        );
    }

    public function save(Customer $customer) : int
    {
        return DB::table($this->table)->insertGetId($customer->toArray());
    }
}
