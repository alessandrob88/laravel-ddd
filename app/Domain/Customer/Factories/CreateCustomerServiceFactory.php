<?php
namespace App\Domain\Customer\Factories;

use App\Domain\Customer\Services\CreateCustomerService;

class CreateCustomerServiceFactory 
{
    public static function create() : CreateCustomerService 
    {
        return new CreateCustomerService(
            CustomerRepositoryFactory::create(),
        );
    }
}