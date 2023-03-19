<?php
namespace App\Domain\Customer\Services;

use App\Domain\Customer\Factories\CustomerModelFactory;
use App\Domain\Customer\Repositories\CustomerRepository;
use App\Domain\Customer\ValueObjects\Customer;
use App\Domain\Customer\Exceptions\CustomerAlreadyExistsException;

class CreateCustomerService 
{
    public function __construct(
        private CustomerRepository $customerRepository,
    )
    {}

    public function create(Customer $customer) : int
    {
        $customerToCreate = CustomerModelFactory::create(
            null,
            $customer->getId(),
            $customer->getBusinessName(),
            $customer->getVat(),
        );

        if(!$foundCustomer = $this->customerRepository->read($customerToCreate->getUuid()))
        {
            return $this->customerRepository->create($customerToCreate);
        }
        
        if($foundCustomer->getVat() === $customerToCreate->getVat() 
        || $foundCustomer->getBusinessName() === $customerToCreate->getBusinessName())
        {
            throw new CustomerAlreadyExistsException("Customer with uuid: {$foundCustomer->getUuid()} already exists!");
        }

        return $customer->getId();
    }
}