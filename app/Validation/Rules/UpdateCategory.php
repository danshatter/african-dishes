<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\Category;

class UpdateCategory extends AbstractRule
{

    public function __construct($category)
    {
        $this->category = $category;
    }

    
    public function validate($input) : bool
    {
        $categories = Category::all()->pluck('name')->toArray();
        
        return !in_array(strtolower($input), $categories) || strtolower($input) === strtolower($this->category);
    }

}