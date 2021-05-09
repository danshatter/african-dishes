<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = ['customer_name', 'item', 'amount', 'email', 'phone', 'address_1', 'address_2', 'payment_method', 'delivery_location', 'payment_reference', 'delivery_fee'];

    protected $attributes = [
        'status' => 0   // 0 -> Awaiting Confirmation, 1 -> Confirmed, 2 -> Disapprove
    ];

    public function getStatusAttribute($value)
    {
        return [
            0 =>  ['status' => 'awaiting confirmation', 'class' => 'info'],
            1 => ['status' => 'confirmed', 'class' => 'success'],
            2 => ['status' => 'disapproved', 'class' => 'danger']
        ][$value];
    }

    public function getItemAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getAmountAttribute($value)
    {
        return number_format($value, 2);
    }

}