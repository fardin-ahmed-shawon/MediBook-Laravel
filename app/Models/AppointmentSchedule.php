<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppointmentSchedule extends Model
{
    use HasFactory;

    protected $table = 'appointment_schedules';

    protected $fillable = [
        'appointment_id',
        'appointment_day',
        'available_start_time',
        'appointment_duration_max',
    ];

    public $timestamps = false;

    /**
     * Get the appointment associated with this schedule.
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get all booked appointments for this schedule.
     */
    public function bookedAppointments(): HasMany
    {
        return $this->hasMany(BookedAppointment::class);
    }
}
