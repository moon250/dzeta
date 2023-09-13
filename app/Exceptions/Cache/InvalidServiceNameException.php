<?php

namespace App\Exceptions\Cache;

use Exception;

class InvalidServiceNameException extends Exception
{
    public function __construct(string $message = '')
    {
        parent::__construct($message);
    }
}
