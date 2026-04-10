<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BookedAppointment;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Get all users (Admin only)
     */
    public function getAllUsers(Request $request)
    {
        if ($request->user()->user_type !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized - Only admins can view all users',
            ], 403);
        }

        $users = User::paginate($request->per_page ?? 15);
        return response()->json($users, 200);
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics(Request $request)
    {
        if ($request->user()->user_type !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized - Only admins can view statistics',
            ], 403);
        }

        $stats = [
            'total_users' => User::count(),
            'total_patients' => User::where('user_type', 'patient')->count(),
            'total_doctors' => User::where('user_type', 'doctor')->count(),
            'total_appointments' => Appointment::count(),
            'total_bookings' => BookedAppointment::count(),
            'active_appointments' => Appointment::count(),
        ];

        return response()->json($stats, 200);
    }

    /**
     * Get all bookings for admin view
     */
    public function getAllBookings(Request $request)
    {
        if ($request->user()->user_type !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized - Only admins can view all bookings',
            ], 403);
        }

        $bookings = BookedAppointment::with(['patient', 'appointment', 'schedule'])
            ->orderBy('appointment_date', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json($bookings, 200);
    }

    /**
     * Delete user (Admin only)
     */
    public function deleteUser(Request $request, $id)
    {
        if ($request->user()->user_type !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized - Only admins can delete users',
            ], 403);
        }

        if ($id === $request->user()->id) {
            return response()->json([
                'message' => 'Cannot delete your own account',
            ], 422);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
