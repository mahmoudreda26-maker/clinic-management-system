<?php

namespace Database\Seeders;

use App\Models\Clinic;
use Illuminate\Database\Seeder;

class ClinicSeeder extends Seeder
{
    public function run(): void
    {
        Clinic::firstOrCreate([
            'name' => 'Demo Clinic',
        ]);
    }
}
