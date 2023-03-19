<?php

namespace App\Domain\Customer\Exceptions;

use Exception;

class CustomerAlreadyExistsException extends Exception
{
    public function __construct($message = "", Exception $previous = null)
    {
        parent::__construct($message, 400, $previous);
    }
}
