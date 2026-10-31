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
        $clinic = Clinic::firstOrCreate([
            'name' => 'Demo Clinic'
        ]);

        $users = [
            [
                'name' => 'Mahmoud',
                'username' => 'mahmoud',
                'email' => 'owner@clinic.test',
                'phone' => '01000000001',
                'role' => 'owner',
            ],
            [
                'name' => 'Yasser',
                'username' => 'yasser',
                'email' => 'doc@clinic.test',
                'phone' => '01000000002',
                'role' => 'doctor',
            ],
            [
                'name' => 'Ali',
                'username' => 'ali',
                'email' => 'recep@clinic.test',
                'phone' => '01000000004',
                'role' => 'receptionist',
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