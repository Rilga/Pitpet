<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetAddon extends Model
{
    protected $fillable = [
        'pet_type',
        'name',
        'description',
        'price_json',
    ];

    protected $casts = [
        'price_json' => 'array',
    ];

    /**
     * Get addon price.
     * Untuk kucing → single price
     * Untuk anjing → size atau price universal
     */
    public function getPrice($size = null)
    {
        $data = $this->price_json;

        // Single price
        if (isset($data['price'])) {
            return $data['price'];
        }

        // Dog addon (size-based)
        if ($size && isset($data[$size])) {
            return $data[$size];
        }

        return 0;
    }
}
