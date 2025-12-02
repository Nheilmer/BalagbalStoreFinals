<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    // Categories → Products: One category can have many products (1:M)
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Products → Inventory: One product has one inventory record (1:1)
    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }
}
