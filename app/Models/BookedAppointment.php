<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookedAppointment extends Model
{
    use HasFactory;

    protected $table = 'booked_appointments';

    protected $fillable = [
        'booking_user_id',
        'appointment_id',
        'appointment_schedule_id',
        'appointment_date',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    public $timestamps = false;

    /**
     * Get the user who booked this appointment.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'booking_user_id');
    }

    /**
     * Get the appointment details.
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the schedule for this booked appointment.
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(AppointmentSchedule::class, 'appointment_schedule_id');
    }
}
