<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_user_id',
        'hospital_location',
        'hospital_name',
        'chamber_location',
        'visiting_fee',
    ];

    protected $casts = [
        'visiting_fee' => 'decimal:2',
    ];

    public $timestamps = false;

    /**
     * Get the doctor user associated with this appointment.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_user_id');
    }

    /**
     * Get the schedules for this appointment.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(AppointmentSchedule::class);
    }

    /**
     * Get all booked appointments for this appointment.
     */
    public function bookedAppointments(): HasMany
    {
        return $this->hasMany(BookedAppointment::class);
    }
}
