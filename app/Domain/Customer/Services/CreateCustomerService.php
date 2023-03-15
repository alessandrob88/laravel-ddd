<?php
namespace App\Domain\Customer\Services;

use App\Domain\Customer\Factories\CustomerModelFactory;
use App\Domain\Customer\Repositories\CustomerRepository;
use App\Domain\Customer\ValueObjects\Customer;

class CreateCustomerService 
{
    public function __construct(
        private CustomerRepository $customerRepository,
    )
    {}

    public function create(Customer $customer) : int
    {
        $customerModel = CustomerModelFactory::create(
            null,
            $customer->getId(),
            $customer->getBusinessName(),
            $customer->getVat(),
        );

        if (!$customer = $this->customerRepository->getByUuid($customerModel->getUuid()))
        {
            return $this->customerRepository->save($customerModel);
        }

        return $customer->getId();
    }
}