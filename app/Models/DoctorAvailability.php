<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorAvailability extends Model
{
    protected $fillable = [ 
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ]; //makes it real data true fauls instend of 0 1

    public function doctor(): BelongsTo //each row belong to 1 dr
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
