<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPet extends Model
{
    protected $fillable = [
        'order_id',
        'pet_type',
        'service_name',
        'service_price',
        'dog_size',
        'quantity',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
