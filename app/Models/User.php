<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $fillable = ['first_name', 'last_name', 'username', 'email', 'phone', 'password', 'phone', 'image', 'address_1', 'address_2'];

    protected $attributes = [
        'role' => 1,
        'recovery' => 0,
        'status' => 1 // 0 => Inactive, 1 => Active, 2 => blocked
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getStatusAttribute($value)
    {
        return [
            0 =>  ['status' => 'inactive', 'class' => 'warning'],
            1 => ['status' => 'active', 'class' => 'success'],
            2 => ['status' => 'blocked', 'class' => 'danger']
        ][$value];
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_DEFAULT);
    }

}