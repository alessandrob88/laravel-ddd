<?php

namespace App\Domain\Invoice\Exceptions;

use Exception;

class InvoiceAlreadyExistsException extends Exception
{
    public function __construct($message = "", Exception $previous = null)
    {
        parent::__construct($message, 400, $previous);
    }
}
