<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class EmailAvailableException extends ValidationException
{
    
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'This email address is already taken'
        ]
    ];
}