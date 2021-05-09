<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\User;

class UpdateEmail extends AbstractRule
{
    
    private $email;

    public function __construct($email)
    {
        $this->email = $email;
    }
    
    public function validate($input) : bool
    {
        return User::where('email', $input)->doesntExist() || strtolower($input) === strtolower($this->email);
    }

}