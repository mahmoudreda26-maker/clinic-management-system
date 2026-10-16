<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\StorePatientRequest;
use App\Http\Requests\Patient\UpdatePatientRequest;
use App\Http\Resources\PatientResource;
use App\Http\Traits\ApiResponse;
use App\Services\PatientService;

class PatientController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly PatientService $patientService
    ) {}

    /**
     * عرض مرضى العيادة الحالية مع Pagination.
     */
    public function index()
    {
        $patients = $this->patientService->getAllPatients();

        return $this->paginated(
            $patients,
            PatientResource::class,
            'تم جلب المرضى بنجاح.'
        );
    }

    /**
     * عرض بيانات مريض محدد.
     */
    public function show(string $patientId)
    {
        $patient = $this->patientService->getPatientById($patientId);

        if (!$patient) {
            return $this->error(
                'المريض غير موجود.',
                404
            );
        }

        return $this->success(
            new PatientResource($patient),
            'تم جلب بيانات المريض بنجاح.'
        );
    }

    /**
     * إنشاء حساب المريض وسجله الطبي الأساسي.
     */
    public function store(StorePatientRequest $request)
    {
        $patient = $this->patientService->createPatient(
            $request->validated()
        );

        return $this->success(
            new PatientResource($patient),
            'تم إنشاء المريض بنجاح.',
            201
        );
    }

    /**
     * تحديث بيانات المريض المحدد.
     */
    public function update(
        UpdatePatientRequest $request,
        string $patientId
    ) {
        $patient = $this->patientService->updatePatient(
            $patientId,
            $request->validated()
        );

        if (!$patient) {
            return $this->error(
                'المريض غير موجود.',
                404
            );
        }

        return $this->success(
            new PatientResource($patient),
            'تم تحديث بيانات المريض بنجاح.'
        );
    }

    /**
     * حذف المريض المحدد في الـ Route.
     */
    public function destroy(string $patientId)
    {
        $deleted = $this->patientService->deletePatient($patientId);

        if (!$deleted) {
            return $this->error(
                'المريض غير موجود.',
                404
            );
        }

        return $this->success(
            null,
            'تم حذف المريض بنجاح.'
        );
    }
}
