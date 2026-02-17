<?php

namespace App\Models;

use App\Enums\enDeliveryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'delivery_date',
        'status',
        'total_items',
        'total_value',
        'vehicle_id',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'status' => enDeliveryStatus::class,
        'total_items' => 'integer',
        'total_value' => 'decimal:2',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(DeliveryItems::class);
    }
}
