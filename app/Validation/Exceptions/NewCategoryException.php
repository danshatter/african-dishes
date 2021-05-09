<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class NewCategoryException extends ValidationException
{
    
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'This category already exists'
        ]
    ];
}