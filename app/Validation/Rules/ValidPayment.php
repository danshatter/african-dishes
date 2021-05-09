<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class ValidPayment extends AbstractRule
{
    
    private $paymentMethods = ['card'];

    public function validate($input) : bool
    {
        return in_array($input, $this->paymentMethods);
    }
}