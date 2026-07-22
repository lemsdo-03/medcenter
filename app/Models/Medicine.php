<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicine extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'code',
        'quantity',
        'min_quantity',
        'price',
        'expiry_date',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function dispenseItems(): HasMany
    {
        return $this->hasMany(DispenseItem::class);
    }

    // FR-44: is this medicine at or below its low-stock threshold?
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->min_quantity;
    }

    // FR-45: has the medicine passed its expiration date?
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }
}
