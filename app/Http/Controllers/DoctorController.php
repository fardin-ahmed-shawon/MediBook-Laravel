<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DoctorController extends Controller
{
    /**
     * Create doctor profile for authenticated user
     */
    public function createProfile(Request $request)
    {
        try {
            // Check if user is a doctor
            if ($request->user()->user_type !== 'doctor') {
                return response()->json([
                    'message' => 'Only users with doctor type can create a doctor profile',
                ], 403);
            }

            // Check if doctor profile already exists
            if (Doctor::where('user_id', $request->user()->id)->exists()) {
                return response()->json([
                    'message' => 'Doctor profile already exists for this user',
                ], 409);
            }

            $validated = $request->validate([
                'specialized_area' => 'nullable|integer|exists:doctors_specialized_categories,id',
                'years_of_experience' => 'nullable|integer|min:0',
            ]);

            $doctor = Doctor::create([
                'user_id' => $request->user()->id,
                'specialized_area' => $validated['specialized_area'] ?? null,
                'years_of_experience' => $validated['years_of_experience'] ?? 0,
            ]);

            return response()->json([
                'message' => 'Doctor profile created successfully',
                'doctor' => $doctor,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Get doctor profile
     */
    public function getProfile(Request $request)
    {
        $doctor = Doctor::where('user_id', $request->user()->id)
            ->with(['user', 'specializedCategory', 'appointments'])
            ->first();

        if (!$doctor) {
            return response()->json(['message' => 'Doctor profile not found'], 404);
        }

        return response()->json($doctor, 200);
    }

    /**
     * Update doctor profile
     */
    public function updateProfile(Request $request)
    {
        try {
            $doctor = Doctor::where('user_id', $request->user()->id)->first();

            if (!$doctor) {
                return response()->json(['message' => 'Doctor profile not found'], 404);
            }

            $validated = $request->validate([
                'specialized_area' => 'nullable|integer|exists:doctors_specialized_categories,id',
                'years_of_experience' => 'nullable|integer|min:0',
            ]);

            $doctor->update($validated);

            return response()->json([
                'message' => 'Doctor profile updated successfully',
                'doctor' => $doctor,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Get all doctors with optional filtering
     */
    public function getAllDoctors(Request $request)
    {
        $query = Doctor::with(['user', 'specializedCategory']);

        if ($request->has('specialized_area')) {
            $query->where('specialized_area', $request->specialized_area);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $doctors = $query->paginate($request->per_page ?? 15);

        return response()->json($doctors, 200);
    }

    /**
     * Get doctor by ID
     */
    public function getDoctorById($id)
    {
        $doctor = Doctor::with(['user', 'specializedCategory', 'appointments.schedules'])
            ->find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        return response()->json($doctor, 200);
    }
}
