<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{

    protected $fillable = ['location', 'lga', 'amount'];
    
    public function setLocationAttribute($value)
    {
        $this->attributes['location'] = strtolower($value);
    }

}