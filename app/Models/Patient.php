<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
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
        'visit_count',
        'reward_available',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'reward_available' => 'boolean',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(PatientNotification::class);
    }

    // FR-70 + FR-71: recount completed visits and grant a reward on every 4th visit
    public function syncVisitsAndReward(): void
    {
        $completed = $this->appointments()->where('status', 'completed')->count();

        // only grant a new reward the moment the patient reaches a new multiple of 4
        $earnedNewReward = $completed > 0
            && $completed % 4 === 0
            && $completed !== $this->visit_count;

        $this->visit_count = $completed;

        if ($earnedNewReward) {
            $this->reward_available = true;
        }

        $this->save();

        if ($earnedNewReward) {
            $this->notifications()->create([
                'title' => 'Loyalty Reward Earned',
                'message' => "Congratulations! You have completed {$completed} visits. A discount or free session is now available on your next appointment.",
            ]);
        }
    }
}
