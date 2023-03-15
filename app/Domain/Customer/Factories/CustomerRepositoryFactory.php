<?php

namespace App\Domain\Customer\Factories;

use App\Domain\Customer\Repositories\CustomerRepository;

class CustomerRepositoryFactory 
{    
    public static function create() : CustomerRepository 
    {
        return new CustomerRepository();
    }
}