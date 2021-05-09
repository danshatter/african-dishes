<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\Menu;

class NewMenu extends AbstractRule
{
    
    public function validate($input) : bool
    {
        $menus = Menu::all()->pluck('name')->toArray();
        
        return !in_array(strtolower($input), $menus);
    }

}