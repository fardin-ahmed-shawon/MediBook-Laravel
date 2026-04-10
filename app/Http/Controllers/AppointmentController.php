<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentSchedule;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    /**
     * Create appointment setup
     */
    public function store(Request $request)
    {
        if ($request->user()->user_type !== 'doctor') {
            return response()->json([
                'message' => 'Only doctors can create appointments',
            ], 403);
        }

        try {
            $validated = $request->validate([
                'hospital_location' => 'nullable|string|max:255',
                'hospital_name' => 'nullable|string|max:150',
                'chamber_location' => 'nullable|string|max:255',
                'visiting_fee' => 'nullable|numeric|min:0',
            ]);

            $appointment = Appointment::create([
                'doctor_user_id' => $request->user()->id,
                'hospital_location' => $validated['hospital_location'] ?? null,
                'hospital_name' => $validated['hospital_name'] ?? null,
                'chamber_location' => $validated['chamber_location'] ?? null,
                'visiting_fee' => $validated['visiting_fee'] ?? 0,
            ]);

            return response()->json([
                'message' => 'Appointment setup created successfully',
                'appointment' => $appointment,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Get appointment details
     */
    public function show($id)
    {
        $appointment = Appointment::with(['doctor', 'schedules', 'bookedAppointments'])
            ->find($id);

        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        return response()->json($appointment, 200);
    }

    /**
     * Update appointment
     */
    public function update(Request $request, $id)
    {
        try {
            $appointment = Appointment::find($id);

            if (!$appointment) {
                return response()->json(['message' => 'Appointment not found'], 404);
            }

            if ($appointment->doctor_user_id !== $request->user()->id) {
                return response()->json([
                    'message' => 'Unauthorized - You can only update your own appointments',
                ], 403);
            }

            $validated = $request->validate([
                'hospital_location' => 'nullable|string|max:255',
                'hospital_name' => 'nullable|string|max:150',
                'chamber_location' => 'nullable|string|max:255',
                'visiting_fee' => 'nullable|numeric|min:0',
            ]);

            $appointment->update($validated);

            return response()->json([
                'message' => 'Appointment updated successfully',
                'appointment' => $appointment,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Delete appointment
     */
    public function destroy(Request $request, $id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        if ($appointment->doctor_user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized - You can only delete your own appointments',
            ], 403);
        }

        $appointment->delete();

        return response()->json(['message' => 'Appointment deleted successfully'], 200);
    }

    /**
     * Get appointments for authenticated doctor
     */
    public function getDoctorAppointments(Request $request)
    {
        if ($request->user()->user_type !== 'doctor') {
            return response()->json([
                'message' => 'Only doctors can view their appointments',
            ], 403);
        }

        $appointments = Appointment::where('doctor_user_id', $request->user()->id)
            ->with(['schedules', 'bookedAppointments'])
            ->paginate($request->per_page ?? 15);

        return response()->json($appointments, 200);
    }

    /**
     * Get available appointments
     */
    public function getAvailableAppointments(Request $request)
    {
        $query = Appointment::with(['doctor', 'schedules']);

        if ($request->has('doctor_id')) {
            $query->where('doctor_user_id', $request->doctor_id);
        }

        $appointments = $query->paginate($request->per_page ?? 15);

        return response()->json($appointments, 200);
    }
}
