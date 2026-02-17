<?php

namespace App\Models;

use App\Enums\enInvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'vehicle_id',
        'invoice_date',
        'start_date',
        'end_date',
        'total_sales',
        'commission_rate',
        'commission_amount',
        'expenses',
        'net_amount',
        'status',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'total_sales' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'expenses' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'status' => enInvoiceStatus::class,
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
