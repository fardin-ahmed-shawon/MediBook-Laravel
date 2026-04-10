<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorSpecializedCategoryController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentScheduleController;
use App\Http\Controllers\BookedAppointmentController;
use App\Http\Controllers\AdminController;

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Get public doctor information
Route::get('/doctors', [DoctorController::class, 'getAllDoctors']);
Route::get('/doctors/{id}', [DoctorController::class, 'getDoctorById']);

// Get specialized categories
Route::get('/specialists', [DoctorSpecializedCategoryController::class, 'index']);
Route::get('/specialists/{id}', [DoctorSpecializedCategoryController::class, 'show']);

// Get available appointments
Route::get('/appointments', [AppointmentController::class, 'getAvailableAppointments']);
Route::get('/appointments/{id}', [AppointmentController::class, 'show']);

// Get schedules for appointment
Route::get('/appointments/{id}/schedules', [AppointmentScheduleController::class, 'getAppointmentSchedules']);

// Protected routes (Authenticated users)
Route::middleware(['auth:sanctum'])->group(function () {
    // Auth routes
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);
    Route::put('/auth/change-password', [AuthController::class, 'changePassword']);

    // Doctor routes
    Route::post('/doctor/profile', [DoctorController::class, 'createProfile']);
    Route::get('/doctor/profile', [DoctorController::class, 'getProfile']);
    Route::put('/doctor/profile', [DoctorController::class, 'updateProfile']);

    // Appointment routes
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::put('/appointments/{id}', [AppointmentController::class, 'update']);
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);
    Route::get('/my-appointments', [AppointmentController::class, 'getDoctorAppointments']);

    // Appointment Schedule routes
    Route::post('/appointment-schedules', [AppointmentScheduleController::class, 'store']);
    Route::put('/appointment-schedules/{id}', [AppointmentScheduleController::class, 'update']);
    Route::delete('/appointment-schedules/{id}', [AppointmentScheduleController::class, 'destroy']);

    // Booked Appointments routes
    Route::post('/bookings', [BookedAppointmentController::class, 'store']);
    Route::get('/bookings/{id}', [BookedAppointmentController::class, 'show']);
    Route::delete('/bookings/{id}', [BookedAppointmentController::class, 'destroy']);
    Route::get('/my-bookings', [BookedAppointmentController::class, 'getPatientBookings']);
    Route::get('/bookings-doctor', [BookedAppointmentController::class, 'getDoctorBookings']);
    Route::get('/appointments/{id}/bookings', [BookedAppointmentController::class, 'getAppointmentBookings']);

    // Admin routes for specialized categories
    Route::post('/specialists', [DoctorSpecializedCategoryController::class, 'store']);
    Route::put('/specialists/{id}', [DoctorSpecializedCategoryController::class, 'update']);
    Route::delete('/specialists/{id}', [DoctorSpecializedCategoryController::class, 'destroy']);

    // Admin routes
    Route::get('/admin/users', [AdminController::class, 'getAllUsers']);
    Route::get('/admin/statistics', [AdminController::class, 'getStatistics']);
    Route::get('/admin/bookings', [AdminController::class, 'getAllBookings']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser']);
});
