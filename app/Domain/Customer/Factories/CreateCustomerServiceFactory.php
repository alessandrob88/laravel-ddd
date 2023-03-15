<?php
namespace App\Domain\Customer\Factories;

use App\Domain\Customer\Repositories\CustomerRepository;
use App\Domain\Customer\Services\CreateCustomerService;

class CreateCustomerServiceFactory 
{
    public static function create (CustomerRepository $customerRepository) : CreateCustomerService 
    {
        return new CreateCustomerService($customerRepository);
    }
}