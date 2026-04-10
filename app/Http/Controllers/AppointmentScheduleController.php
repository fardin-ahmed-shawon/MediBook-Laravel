<?php

namespace App\Http\Controllers;

use App\Models\AppointmentSchedule;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AppointmentScheduleController extends Controller
{
    /**
     * Create appointment schedule
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'appointment_id' => 'required|integer|exists:appointments,id',
                'appointment_day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
                'available_start_time' => 'required|date_format:H:i',
                'appointment_duration_max' => 'required|integer|min:15',
            ]);

            // Check if appointment belongs to authenticated doctor
            $appointment = Appointment::find($validated['appointment_id']);
            if ($appointment->doctor_user_id !== $request->user()->id) {
                return response()->json([
                    'message' => 'Unauthorized - You can only add schedules to your own appointments',
                ], 403);
            }

            $schedule = AppointmentSchedule::create($validated);

            return response()->json([
                'message' => 'Appointment schedule created successfully',
                'schedule' => $schedule,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Get schedule details
     */
    public function show($id)
    {
        $schedule = AppointmentSchedule::with(['appointment', 'bookedAppointments'])
            ->find($id);

        if (!$schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }

        return response()->json($schedule, 200);
    }

    /**
     * Update appointment schedule
     */
    public function update(Request $request, $id)
    {
        try {
            $schedule = AppointmentSchedule::find($id);

            if (!$schedule) {
                return response()->json(['message' => 'Schedule not found'], 404);
            }

            $appointment = $schedule->appointment;
            if ($appointment->doctor_user_id !== $request->user()->id) {
                return response()->json([
                    'message' => 'Unauthorized - You can only update your own schedules',
                ], 403);
            }

            $validated = $request->validate([
                'appointment_day' => 'sometimes|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
                'available_start_time' => 'sometimes|date_format:H:i',
                'appointment_duration_max' => 'sometimes|integer|min:15',
            ]);

            $schedule->update($validated);

            return response()->json([
                'message' => 'Schedule updated successfully',
                'schedule' => $schedule,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Delete appointment schedule
     */
    public function destroy(Request $request, $id)
    {
        $schedule = AppointmentSchedule::find($id);

        if (!$schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }

        $appointment = $schedule->appointment;
        if ($appointment->doctor_user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized - You can only delete your own schedules',
            ], 403);
        }

        $schedule->delete();

        return response()->json(['message' => 'Schedule deleted successfully'], 200);
    }

    /**
     * Get schedules for an appointment
     */
    public function getAppointmentSchedules($appointmentId)
    {
        $appointment = Appointment::find($appointmentId);

        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        $schedules = AppointmentSchedule::where('appointment_id', $appointmentId)
            ->with(['bookedAppointments'])
            ->get();

        return response()->json($schedules, 200);
    }
}
