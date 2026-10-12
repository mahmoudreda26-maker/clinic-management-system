<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $clinic = Clinic::firstOrCreate(
            ['name' => 'Demo Clinic']
        );

        $users = [
            [
                'name' => 'Mahmoud',
                'email' => 'owner@clinic.test',
                'phone' => '01000000001',
                'role' => 'owner',
            ],
            [
                'name' => 'yasser',
                'email' => 'doc@clinic.test',
                'phone' => '01000000002',
                'role' => 'doctor',
            ],
            [
                'name' => 'Reda',
                'email' => 'nur@clinic.test',
                'phone' => '01000000003',
                'role' => 'nurse',
            ],
            [
                'name' => 'Ali',
                'email' => 'recep@clinic.test',
                'phone' => '01000000004',
                'role' => 'receptionist',
            ],
            [
                'name' => 'Ahmed',
                'email' => 'pat@clinic.test',
                'phone' => '01000000005',
                'role' => 'patient',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    ...$userData,
                    'clinic_id' => $clinic->id,
                    'password' => Hash::make('123456789'),
                    'is_active' => true,
                ]
            );
        }
    }
}
