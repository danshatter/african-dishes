<?php

namespace App\Base;

use Carbon\Carbon;

class TwigRuntimeExtension
{

    public function intPrice($price) : int
    {
        // split price into two parts
        $parts = explode('.', $price);

        $price = explode(',', reset($parts));

        $intPrice = implode('', $price);

        return (int) $intPrice;
    }

    public function diffForHumans($date) : string
    {
        $now = Carbon::now();

        return $now->parse($date)->diffForHumans();
    }

}