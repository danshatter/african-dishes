<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\User;

class ValidEmail extends AbstractRule
{
    
    public function validate($input) : bool
    {
        return User::where('email', $input)->exists();
    }
}