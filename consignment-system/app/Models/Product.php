<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'sku',
        'default_price',
        'cost_price',
    ];

    protected $casts = [
        'default_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
    ];

    public function deliveryItems(): HasMany
    {
        return $this->hasMany(DeliveryItems::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItems::class);
    }
}
