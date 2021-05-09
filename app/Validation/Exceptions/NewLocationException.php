<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class NewLocationException extends ValidationException
{
    
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'Location already added'
        ]
    ];
}