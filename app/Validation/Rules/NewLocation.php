<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\Location;

class NewLocation extends AbstractRule
{
    
    public function validate($input) : bool
    {
        $locations = Location::all()->pluck('location')->toArray();
        
        return !in_array(strtolower($input), $locations);
    }

}