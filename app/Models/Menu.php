<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{

    protected $fillable = ['name', 'category_id', 'description', 'quantity', 'price', 'image_extension'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getPriceAttribute($value)
    {
        return number_format($value, 2);
    }

}