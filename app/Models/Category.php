<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
    ];

    // FR-39: a category can hold many medicines (used to block deletion if not empty)
    public function medicines(): HasMany
    {
        return $this->hasMany(Medicine::class);
    }
}
