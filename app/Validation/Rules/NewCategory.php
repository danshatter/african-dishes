<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\Category;

class NewCategory extends AbstractRule
{
    
    public function validate($input) : bool
    {
        $categories = Category::all()->pluck('name')->toArray();
        return !in_array(strtolower($input), $categories);
    }

}