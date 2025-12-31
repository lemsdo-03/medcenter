<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyNotification extends Model
{
    protected $fillable = [
        'doctor_id',
        'receptionist_id',
        'message',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function receptionist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receptionist_id');
    }
}


