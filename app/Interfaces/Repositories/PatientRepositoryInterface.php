<?php

namespace App\Interfaces\Repositories;

use App\Models\Patient;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PatientRepositoryInterface
{
 
    public function paginateByClinic(
        string $clinicId,
        int $perPage = 15
    ): LengthAwarePaginator;

 
    public function findByIdForClinic(
        string $patientId,
        string $clinicId
    ): ?Patient;


    public function create(array $data): Patient;


    public function update(
        Patient $patient,
        array $data
    ): Patient;


    public function delete(Patient $patient): bool;
}
