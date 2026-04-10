<?php

namespace App\Http\Controllers;

use App\Models\BookedAppointment;
use App\Models\AppointmentSchedule;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookedAppointmentController extends Controller
{
    /**
     * Create booking for patient
     */
    public function store(Request $request)
    {
        if ($request->user()->user_type !== 'patient') {
            return response()->json([
                'message' => 'Only patients can book appointments',
            ], 403);
        }

        try {
            $validated = $request->validate([
                'appointment_id' => 'required|integer|exists:appointments,id',
                'appointment_schedule_id' => 'required|integer|exists:appointment_schedules,id',
                'appointment_date' => 'required|date|after:today',
            ]);

            // Validate that appointment_schedule_id belongs to appointment_id
            $schedule = AppointmentSchedule::where('id', $validated['appointment_schedule_id'])
                ->where('appointment_id', $validated['appointment_id'])
                ->first();

            if (!$schedule) {
                return response()->json([
                    'message' => 'Selected schedule does not belong to the selected appointment',
                ], 422);
            }

            // Check for duplicate booking on same date/schedule
            $existingBooking = BookedAppointment::where('booking_user_id', $request->user()->id)
                ->where('appointment_schedule_id', $validated['appointment_schedule_id'])
                ->where('appointment_date', $validated['appointment_date'])
                ->first();

            if ($existingBooking) {
                return response()->json([
                    'message' => 'You already have a booking for this slot on the selected date',
                ], 409);
            }

            $booking = BookedAppointment::create([
                'booking_user_id' => $request->user()->id,
                'appointment_id' => $validated['appointment_id'],
                'appointment_schedule_id' => $validated['appointment_schedule_id'],
                'appointment_date' => $validated['appointment_date'],
            ]);

            return response()->json([
                'message' => 'Appointment booked successfully',
                'booking' => $booking->load(['patient', 'appointment', 'schedule']),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Get booking details
     */
    public function show($id)
    {
        $booking = BookedAppointment::with(['patient', 'appointment', 'schedule'])
            ->find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        return response()->json($booking, 200);
    }

    /**
     * Cancel booking
     */
    public function destroy(Request $request, $id)
    {
        $booking = BookedAppointment::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Allow patient to cancel their own booking or doctor/admin to cancel
        if ($booking->booking_user_id !== $request->user()->id && $request->user()->user_type === 'patient') {
            return response()->json([
                'message' => 'Unauthorized - You can only cancel your own bookings',
            ], 403);
        }

        $booking->delete();

        return response()->json(['message' => 'Booking cancelled successfully'], 200);
    }

    /**
     * Get patient's bookings
     */
    public function getPatientBookings(Request $request)
    {
        if ($request->user()->user_type !== 'patient') {
            return response()->json([
                'message' => 'Only patients can view their bookings',
            ], 403);
        }

        $bookings = BookedAppointment::where('booking_user_id', $request->user()->id)
            ->with(['appointment', 'schedule'])
            ->orderBy('appointment_date', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json($bookings, 200);
    }

    /**
     * Get doctor's bookings
     */
    public function getDoctorBookings(Request $request)
    {
        if ($request->user()->user_type !== 'doctor') {
            return response()->json([
                'message' => 'Only doctors can view bookings for their appointments',
            ], 403);
        }

        $bookings = BookedAppointment::whereHas('appointment', function ($q) use ($request) {
            $q->where('doctor_user_id', $request->user()->id);
        })
        ->with(['patient', 'appointment', 'schedule'])
        ->orderBy('appointment_date', 'desc')
        ->paginate($request->per_page ?? 15);

        return response()->json($bookings, 200);
    }

    /**
     * Get bookings for specific appointment
     */
    public function getAppointmentBookings($appointmentId, Request $request)
    {
        $appointment = Appointment::find($appointmentId);

        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        // Only doctor can view bookings for their appointment
        if ($appointment->doctor_user_id !== $request->user()->id && $request->user()->user_type !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $bookings = BookedAppointment::where('appointment_id', $appointmentId)
            ->with(['patient', 'schedule'])
            ->orderBy('appointment_date', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json($bookings, 200);
    }
}
