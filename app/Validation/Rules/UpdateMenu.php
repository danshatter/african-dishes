<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\Menu;

class UpdateMenu extends AbstractRule
{

    public function __construct($menu)
    {
        $this->menu = $menu;
    }

    
    public function validate($input) : bool
    {
        $menus = Menu::all()->pluck('name')->toArray();
        
        return !in_array(strtolower($input), $menus) || strtolower($input) === strtolower($this->menu);
    }

}