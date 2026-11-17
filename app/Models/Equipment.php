<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equipment extends Model
{
    public const CATEGORIES = ['paddles', 'balls', 'nets', 'accessories'];

    public const AVAILABILITY_STATUSES = ['available', 'unavailable'];

    protected $fillable = [
        'equipment_name',
        'category',
        'unit_price',
        'description',
        'availability_status',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
