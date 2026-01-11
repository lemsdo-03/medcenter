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

    public function doctor(): BelongsTo   //one dr to one noti
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function receptionist(): BelongsTo //one rece to one noti
    {
        return $this->belongsTo(User::class, 'receptionist_id');
    }
}


