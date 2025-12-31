<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'phone',
        'email',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'medical_history',
        'allergies',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalNotes(): HasMany
    {
        return $this->hasMany(MedicalNote::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
