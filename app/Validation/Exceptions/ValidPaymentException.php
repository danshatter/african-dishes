<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class ValidPaymentException extends ValidationException
{
    
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'Invalid payment method'
        ]
    ];
}