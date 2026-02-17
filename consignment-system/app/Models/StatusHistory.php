<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_status',
        'to_status',
        'changed_by',
    ];

    protected $casts = [
        'from_status' => 'integer',
        'to_status' => 'integer',
        'changed_by' => 'integer',
    ];

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }
}
