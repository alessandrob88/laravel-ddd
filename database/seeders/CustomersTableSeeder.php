<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DateTimeImmutable;

class CustomersTableSeeder extends Seeder
{
    public function run(int $count)
    {
        $now = new DateTimeImmutable();

        $customers = [];
        for($i = 0; $i < $count; $i++)
        {
            $customers[] = [
                'uuid' => Str::uuid(),
                'business_name' => 'Foo Srl',
                'vat' => '1234567'.str_pad($i, 3, 0, STR_PAD_LEFT),
                'created_at' => $now,
                'updated_at' => $now,
            ]; 
        }

        for($i = 0; $i < count($customers); $i++){
            $customers[$i]['id'] = DB::table('customers')->insertGetId($customers[$i]);
        }

        return $customers;
       
    }
}
