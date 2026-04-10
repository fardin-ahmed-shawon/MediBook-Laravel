<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialized_area',
        'years_of_experience',
    ];

    public $timestamps = false;

    /**
     * Get the user associated with this doctor.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the specialized category for this doctor.
     */
    public function specializedCategory(): BelongsTo
    {
        return $this->belongsTo(DoctorSpecializedCategory::class, 'specialized_area');
    }

    /**
     * Get the appointments for this doctor.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'doctor_user_id', 'user_id');
    }
}
