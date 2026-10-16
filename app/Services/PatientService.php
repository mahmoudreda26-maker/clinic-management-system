<?php

namespace App\Services;

use App\Interfaces\Repositories\PatientRepositoryInterface;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PatientService
{
    public function __construct(
        private readonly PatientRepositoryInterface $patientRepository
    ) {}

    /**
     * جلب مرضى العيادة الحالية فقط.
     */
    public function getAllPatients(): LengthAwarePaginator
    {
        $user = auth()->user();

        return $this->patientRepository->paginateByClinic(
            $user->clinic_id
        );
    }

    /**
     * جلب مريض باستخدام ID مع مراعاة Tenant Isolation.
     */
    public function getPatientById(string $patientId): ?Patient
    {
        $user = auth()->user();

        return $this->patientRepository->findByIdForClinic(
            $patientId,
            $user->clinic_id
        );
    }

    /**
     * إنشاء حساب دخول للمريض ثم إنشاء سجل المريض وربطهما.
     */
    public function createPatient(array $data): Patient
    {
        $currentUser = auth()->user();

        return DB::transaction(function () use ($data, $currentUser) {
            $emailExists = User::query()
                ->where('email', $data['email'])
                ->exists();

            if ($emailExists) {
                throw ValidationException::withMessages([
                    'email' => ['البريد الإلكتروني مستخدم بالفعل.'],
                ]);
            }

            $patientUser = User::create([
                'name'      => $data['full_name'],
                'email'     => $data['email'],
                'phone'     => $data['phone'],
                'password'  => Hash::make($data['password']),
                'role'      => 'patient',
                'clinic_id' => $currentUser->clinic_id,
            ]);

            unset($data['email'], $data['password']);

            $data['user_id'] = $patientUser->id;
            $data['clinic_id'] = $currentUser->clinic_id;

            $patient = $this->patientRepository->create($data);

            return $patient->load('user');
        });
    }

    /**
     * تحديث مريض داخل العيادة الحالية فقط.
     */
    public function updatePatient(
        string $patientId,
        array $data
    ): ?Patient {
        $patient = $this->getPatientById($patientId);

        if (!$patient) {
            return null;
        }

        // منع تغيير العيادة أو حساب المستخدم من خلال التحديث.
        unset($data['clinic_id'], $data['user_id']);

        return $this->patientRepository->update($patient, $data);
    }

    
/**
 * حذف سجل المريض وحساب الدخول المرتبط به.
 */
public function deletePatient(string $patientId): bool
{
    $patient = $this->getPatientById($patientId);

    if (!$patient) {
        return false;
    }

    return DB::transaction(function () use ($patient) {

        $patientUser = $patient->user;

        $deleted = $this->patientRepository->delete($patient);

        if (!$deleted) {
            throw new \RuntimeException(
                'Failed to delete patient record.'
            );
        }

        if ($patientUser) {
            $patientUser->delete();
        }

        return true;
    });
}

}
