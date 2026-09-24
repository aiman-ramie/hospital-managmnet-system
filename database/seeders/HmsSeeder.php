<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class HmsSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            ['name' => 'Dr. Shah Ahmed', 'specialty' => 'Cardiology', 'schedule' => '09:00 - 16:00', 'room' => '101', 'status' => 'Available'],
            ['name' => 'Dr. Ali Raza', 'specialty' => 'General Medicine', 'schedule' => '10:00 - 18:00', 'room' => '204', 'status' => 'Available'],
            ['name' => 'Dr. Sarah Ahmed', 'specialty' => 'Pediatrics', 'schedule' => '08:00 - 14:00', 'room' => '112', 'status' => 'In clinic'],
            ['name' => 'Dr. Hamza Malik', 'specialty' => 'Orthopedics', 'schedule' => '11:00 - 19:00', 'room' => '208', 'status' => 'Off duty'],
        ];

        foreach ($doctors as $doctor) {
            Doctor::firstOrCreate(['name' => $doctor['name']], $doctor);
        }

        $patients = [
            ['patient_code' => 'PT-10248', 'name' => 'Khawar Ali', 'gender' => 'Male', 'age' => 32, 'phone' => '+92 300 1234567', 'department' => 'General Ward', 'status' => 'Active', 'last_visit' => now()->toDateString()],
            ['patient_code' => 'PT-10247', 'name' => 'Razia Bibi', 'gender' => 'Female', 'age' => 45, 'phone' => '+92 312 9876543', 'department' => 'Female Ward', 'status' => 'Active', 'last_visit' => now()->toDateString()],
            ['patient_code' => 'PT-10246', 'name' => 'Muhammad Asif', 'gender' => 'Male', 'age' => 58, 'phone' => '+92 333 7654321', 'department' => 'ICU', 'status' => 'Pending', 'last_visit' => now()->toDateString()],
            ['patient_code' => 'PT-10245', 'name' => 'Nida Khan', 'gender' => 'Female', 'age' => 29, 'phone' => '+92 321 1122334', 'department' => 'Private Room', 'status' => 'Active', 'last_visit' => now()->toDateString()],
        ];

        foreach ($patients as $patient) {
            Patient::firstOrCreate(['patient_code' => $patient['patient_code']], $patient);
        }
    }
}
