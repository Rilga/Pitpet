<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_address',
        'date',
        'time_slot',
        'distance_km',
        'transport_fee',
        'subtotal',
        'total',
        'status',
        'groomer_id',
    ];

    public function pets()
    {
        return $this->hasMany(OrderPet::class);
    }

    public function groomer()
    {
        // 'groomer_id' adalah nama kolom di tabel orders
        return $this->belongsTo(User::class, 'groomer_id');
    }
}
