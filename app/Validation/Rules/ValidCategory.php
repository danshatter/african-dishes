<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\Category;

class ValidCategory extends AbstractRule
{
    
    public function validate($input) : bool
    {
        $categories = Category::all();

        foreach($categories as $category) {
            if ($category->id == $input) {
                return true;
            }
        }

        return false;
    }

}