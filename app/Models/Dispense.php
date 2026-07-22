<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dispense extends Model
{
    protected $fillable = [
        'patient_id',
        'medical_note_id',
        'pharmacist_id',
        'total',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function medicalNote(): BelongsTo
    {
        return $this->belongsTo(MedicalNote::class);
    }

    public function pharmacist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pharmacist_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(DispenseItem::class);
    }
}
