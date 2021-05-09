<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\User;

class ValidOldPassword extends AbstractRule
{

    private $password;

    public function __construct($password)
    {
        $this->password = $password;
    }
    
    public function validate($input) : bool
    {
        return password_verify($input, $this->password);
    }
}