<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'full_name' => 'required|string|max:150',
                'phone' => 'required|string|max:20|unique:users',
                'email' => 'nullable|email|max:150|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'user_type' => 'required|in:patient,doctor,admin',
            ]);

            $user = User::create([
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'password_hashed' => Hash::make($validated['password']),
                'user_type' => $validated['user_type'],
            ]);

            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user,
                'token' => $user->createToken('API Token')->plainTextToken,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'phone' => 'required|string',
                'password' => 'required|string',
            ]);

            $user = User::where('phone', $validated['phone'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password_hashed)) {
                throw ValidationException::withMessages([
                    'phone' => ['The provided credentials are incorrect.'],
                ]);
            }

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $user->createToken('API Token')->plainTextToken,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Login failed',
                'errors' => $e->errors(),
            ], 401);
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    /**
     * Get current authenticated user
     */
    public function me(Request $request)
    {
        return response()->json($request->user(), 200);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                'full_name' => 'sometimes|string|max:150',
                'email' => 'sometimes|email|max:150|unique:users,email,' . $request->user()->id,
                'phone' => 'sometimes|string|max:20|unique:users,phone,' . $request->user()->id,
            ]);

            $request->user()->update($validated);

            return response()->json([
                'message' => 'Profile updated successfully',
                'user' => $request->user(),
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'old_password' => 'required|string',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if (!Hash::check($validated['old_password'], $request->user()->password_hashed)) {
                throw ValidationException::withMessages([
                    'old_password' => ['The provided password does not match your current password.'],
                ]);
            }

            $request->user()->update([
                'password_hashed' => Hash::make($validated['password']),
            ]);

            return response()->json(['message' => 'Password changed successfully'], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
