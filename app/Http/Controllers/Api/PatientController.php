<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Http\Requests\Patient\StorePatientRequest;
use App\Http\Requests\Patient\UpdatePatientRequest;
use App\Http\Resources\PatientResource;
use App\Services\PatientService;

class PatientController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly PatientService $patientService
    ) {}

    // Get patient by ID
    public function getPatientById(string $patientId)
    {
        $patient = $this->patientService->getPatientById($patientId);

        return $this->success(
            new PatientResource($patient),
            'Patient retrieved successfully'
        );
    }

    // Get all patients
    public function getAllPatients()
    {
        $patients = $this->patientService->getAllPatients();

        return $this->success(
            PatientResource::collection($patients),
            'Patients retrieved successfully'
        );
    }

    // Create patient
    public function createPatient(StorePatientRequest $request)
    {
        $patient = $this->patientService->createPatient(
            $request->validated()
        );

        return $this->success(
            new PatientResource($patient),
            'Patient created successfully'
        );
    }

    // Update patient
    public function updatePatient(
        UpdatePatientRequest $request,
        string $patientId
    ) {
        $patient = $this->patientService->updatePatient(
            $patientId,
            $request->validated()
        );

        return $this->success(
            new PatientResource($patient),
            'Patient updated successfully'
        );
    }

    // Delete patient
    public function deletePatient(string $patientId)
    {
        $this->patientService->deletePatient($patientId);

        return $this->success(
            null,
            'Patient deleted successfully'
        );
    }
}