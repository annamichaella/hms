<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ward;
use App\Models\Appointment;
use App\Models\Billing;
use App\Models\PatientRecord;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'fname' => 'Admin',
            'lname' => 'User',
            'email' => 'admin@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1234567890',
            'address' => '123 Admin Street',
        ]);

        // Create doctor users
        $doctor1 = User::create([
            'fname' => 'John',
            'lname' => 'Smith',
            'email' => 'doctor1@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'phone' => '+1234567891',
            'address' => '456 Doctor Lane',
            'specialization' => 'Cardiology',
            'department' => 'Cardiology',
        ]);

        $doctor2 = User::create([
            'fname' => 'Sarah',
            'lname' => 'Johnson',
            'email' => 'doctor2@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'phone' => '+1234567892',
            'address' => '789 Medical Ave',
            'specialization' => 'Pediatrics',
            'department' => 'Pediatrics',
        ]);

        // Create nurse users
        $nurse1 = User::create([
            'fname' => 'Emily',
            'lname' => 'Davis',
            'email' => 'nurse1@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'nurse',
            'phone' => '+1234567893',
            'address' => '321 Nurse Blvd',
            'department' => 'Emergency',
        ]);

        // Create staff users
        $staff1 = User::create([
            'fname' => 'Michael',
            'lname' => 'Wilson',
            'email' => 'staff1@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone' => '+1234567894',
            'address' => '654 Staff Road',
            'department' => 'Administration',
        ]);

        // Create patient users
        $patient1 = User::create([
            'fname' => 'Alice',
            'lname' => 'Brown',
            'email' => 'patient1@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'patient',
            'phone' => '+1234567895',
            'address' => '987 Patient Street',
        ]);

        $patient2 = User::create([
            'fname' => 'Bob',
            'lname' => 'Miller',
            'email' => 'patient2@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'patient',
            'phone' => '+1234567896',
            'address' => '147 Health Lane',
        ]);

        // Create wards
        $ward1 = Ward::create([
            'ward_name' => 'Cardiology Ward A',
            'ward_type' => 'General',
            'floor' => '2nd Floor',
            'capacity' => 20,
            'status' => 'Active',
        ]);

        $ward2 = Ward::create([
            'ward_name' => 'ICU Unit 1',
            'ward_type' => 'ICU',
            'floor' => '3rd Floor',
            'capacity' => 8,
            'status' => 'Active',
        ]);

        // Create appointments
        Appointment::create([
            'patient_id' => $patient1->id,
            'doctor_id' => $doctor1->id,
            'appointment_date' => now()->addDays(3),
            'appointment_time' => '10:00:00',
            'reason' => 'Regular checkup',
            'status' => 'confirmed',
        ]);

        Appointment::create([
            'patient_id' => $patient2->id,
            'doctor_id' => $doctor2->id,
            'appointment_date' => now()->addDays(5),
            'appointment_time' => '14:30:00',
            'reason' => 'Consultation',
            'status' => 'pending',
        ]);

        // Create patient records
        PatientRecord::create([
            'user_id' => $patient1->id,
            'blood_type' => 'A+',
            'allergies' => 'Penicillin',
            'medical_conditions' => 'Hypertension',
            'emergency_contact_name' => 'John Brown',
            'emergency_contact_phone' => '+1234567897',
        ]);

        // Create billing records
        Billing::create([
            'patient_name' => $patient1->full_name,
            'doctor_name' => $doctor1->full_name,
            'service' => 'Consultation',
            'amount' => 150.00,
            'status' => 'pending',
            'billing_date' => now(),
            'due_date' => now()->addDays(30),
        ]);
    }
}
