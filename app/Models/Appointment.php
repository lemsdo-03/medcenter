<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appointment extends Model
{
    protected $fillable = [ //this can be filled in the appointment table
        'patient_id',
        'doctor_id',
        'appointment_date',
        'status',
        'notes',
        'is_paid',
    ];

    protected $casts = [ //real time
        'appointment_date' => 'datetime',
        'is_paid' => 'boolean',
    ];

    public function patient(): BelongsTo //one patien on appointment 
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo //one dr on appoin
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function medicalNotes(): HasMany //one appointment can have amny med notes
    {
        return $this->hasMany(MedicalNote::class);
    }
}
