<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\Location;

class UpdateLocation extends AbstractRule
{
    
    private $location;

    public function __construct($location)
    {
        $this->location = $location;
    }
    
    public function validate($input) : bool
    {
        return Location::where('location', $input)->doesntExist() || strtolower($input) === strtolower($this->location);
    }

}