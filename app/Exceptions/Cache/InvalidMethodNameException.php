<?php

namespace App\Exceptions\Cache;

use Exception;

class InvalidMethodNameException extends Exception
{
    public function __construct(string $message = '')
    {
        parent::__construct($message);
    }
}
