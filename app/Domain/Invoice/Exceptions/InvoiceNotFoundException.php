<?php

namespace App\Domain\Invoice\Exceptions;

use Exception;

class InvoiceNotFoundException extends Exception
{
    public function __construct($message = "", Exception $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}
