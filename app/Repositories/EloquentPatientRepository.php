<?php

namespace App\Repositories;

use App\Interfaces\Repositories\PatientRepositoryInterface;
use App\Models\Patient;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentPatientRepository implements PatientRepositoryInterface
{
    /**
     * جلب مرضى العيادة الحالية بترتيب الأحدث.
     */
    public function paginateByClinic(
        string $clinicId,
        int $perPage = 15
    ): LengthAwarePaginator {
        return Patient::query()
            ->where('clinic_id', $clinicId)
            ->with('user')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * جلب مريض محدد بشرط أن يكون تابعًا لنفس العيادة.
     */
    public function findByIdForClinic(
        string $patientId,
        string $clinicId
    ): ?Patient {
        return Patient::query()
            ->where('clinic_id', $clinicId)
            ->whereKey($patientId)
            ->with('user')
            ->first();
    }

    /**
     * إنشاء المريض وربطه بالعيادة وحساب المستخدم.
     */
    public function create(array $data): Patient
    {
        $clinicId = $data['clinic_id'];
        $userId = $data['user_id'];

        $patient = new Patient(
            collect($data)
                ->except(['clinic_id', 'user_id'])
                ->all()
        );

        $patient->clinic_id = $clinicId;
        $patient->user_id = $userId;

        $patient->save();

        return $patient->load('user');
    }

    /**
     * تحديث بيانات المريض المحدد.
     */
    public function update(
        Patient $patient,
        array $data
    ): Patient {
        unset($data['clinic_id'], $data['user_id']);

        $patient->update($data);

        return $patient->refresh()->load('user');
    }

    /**
     * حذف سجل المريض الذي تم تمريره فقط.
     */
    public function delete(Patient $patient): bool
    {
        return $patient->delete();
    }
}
