<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use App\Models\DoctorSpecializedCategory;
use App\Models\Appointment;
use App\Models\AppointmentSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create specialized categories
        $categories = [
            ['name' => 'Cardiology'],
            ['name' => 'Neurology'],
            ['name' => 'Orthopedics'],
            ['name' => 'Dermatology'],
            ['name' => 'Pediatrics'],
            ['name' => 'Psychiatry'],
            ['name' => 'General Surgery'],
            ['name' => 'ENT'],
        ];

        foreach ($categories as $category) {
            DoctorSpecializedCategory::create($category);
        }

        // Create admin user
        $admin = User::create([
            'full_name' => 'Admin User',
            'phone' => '01700000000',
            'email' => 'admin@example.com',
            'password_hashed' => Hash::make('password'),
            'user_type' => 'admin',
        ]);

        // Create sample patient users
        $patients = [];
        for ($i = 1; $i <= 5; $i++) {
            $patients[] = User::create([
                'full_name' => "Patient $i",
                'phone' => '0170000' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'email' => "patient{$i}@example.com",
                'password_hashed' => Hash::make('password'),
                'user_type' => 'patient',
            ]);
        }

        // Create sample doctor users with profiles
        $doctorCategories = DoctorSpecializedCategory::all();
        
        for ($i = 1; $i <= 5; $i++) {
            $doctor = User::create([
                'full_name' => "Dr. Doctor $i",
                'phone' => '0171000' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'email' => "doctor{$i}@example.com",
                'password_hashed' => Hash::make('password'),
                'user_type' => 'doctor',
            ]);

            // Create doctor profile
            Doctor::create([
                'user_id' => $doctor->id,
                'specialized_area' => $doctorCategories->random()->id,
                'years_of_experience' => rand(1, 30),
            ]);

            // Create appointments for doctors
            for ($j = 1; $j <= 2; $j++) {
                $appointment = Appointment::create([
                    'doctor_user_id' => $doctor->id,
                    'hospital_name' => "Hospital $i",
                    'hospital_location' => "Location $i",
                    'chamber_location' => "Chamber $j",
                    'visiting_fee' => rand(300, 1500),
                ]);

                // Create schedules for appointments
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                foreach ($days as $day) {
                    AppointmentSchedule::create([
                        'appointment_id' => $appointment->id,
                        'appointment_day' => $day,
                        'available_start_time' => '09:00',
                        'appointment_duration_max' => 30,
                    ]);
                }
            }
        }
    }
}
