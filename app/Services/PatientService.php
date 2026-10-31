<?php

namespace App\Services;

use App\Models\Patient;

class PatientService
{
    public function getPatientById(string $patientId)
    {
        $patient = Patient::where('clinic_id', auth()->user()->clinic_id)
            ->findOrFail($patientId);

        return $patient;
    }

    public function getAllPatients()
    {
        return Patient::where('clinic_id', auth()->user()->clinic_id)
            ->get();
    }

    public function createPatient(array $data)
    {
        return Patient::create([
            'name'       => $data['name'],
            'phone'      => $data['phone'],
            'national_id' => $data['national_id'],
            'clinic_id'  => auth()->user()->clinic_id,
        ]);
    }

    public function updatePatient(string $patientId, array $data)
    {
        $patient = Patient::where(
            'clinic_id',
            auth()->user()->clinic_id
        )->findOrFail($patientId);

        $patient->fill($data);
        $patient->save();

        return $patient->fresh();
    }

    public function deletePatient(string $patientId)
    {
        $patient = Patient::where('clinic_id', auth()->user()->clinic_id)
            ->findOrFail($patientId);

        return $patient->delete();
    }
}
