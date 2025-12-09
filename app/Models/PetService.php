<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetService extends Model
{
    protected $fillable = [
        'pet_type',
        'name',
        'description',
        'price_json',
    ];

    protected $casts = [
        'price_json' => 'array', // otomatis decode JSON ke array
    ];

    /**
     * Get price.
     * Untuk kucing → return single price
     * Untuk anjing → return price per size (array)
     */
    public function getPrice($size = null)
    {
        // Cat → single price
        if ($this->pet_type === 'cat') {
            return $this->price_json['price'];
        }

        // Dog → butuh size
        if ($this->pet_type === 'dog') {
            if ($size && isset($this->price_json[$size])) {
                return $this->price_json[$size];
            }
        }

        return null;
    }
}
