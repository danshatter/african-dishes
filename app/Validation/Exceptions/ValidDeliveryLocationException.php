<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class ValidDeliveryLocationException extends ValidationException
{
    
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'Invalid delivery location chosen'
        ]
    ];
}