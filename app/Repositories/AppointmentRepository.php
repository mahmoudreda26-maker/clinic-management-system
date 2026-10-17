<?php

namespace App\Repositories;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;

class AppointmentRepository
{
    /**
     * جلب جميع مواعيد العيادة.
     */
    public function getAllAppointments(string $clinicId)
    {
        return Appointment::query()
            ->where('clinic_id', $clinicId)
            ->with(['patient', 'user'])
            ->latest()
            ->paginate(15);
    }

    /**
     * جلب موعد محدد داخل العيادة.
     */
    public function getAppointmentById(
        string $id,
        string $clinicId
    ): Appointment {
        return Appointment::query()
            ->where('clinic_id', $clinicId)
            ->where('id', $id)
            ->with(['patient', 'user'])
            ->firstOrFail();
    }

    /**
     * التحقق من أن المريض تابع للعيادة.
     */
    public function patientBelongsToClinic(
        string $patientId,
        string $clinicId
    ): bool {
        return Patient::query()
            ->where('clinic_id', $clinicId)
            ->where('id', $patientId)
            ->exists();
    }

    /**
     * التحقق من أن الطبيب تابع للعيادة ونشط.
     */
    public function doctorBelongsToClinic(
        string $doctorId,
        string $clinicId
    ): bool {
        return User::query()
            ->where('clinic_id', $clinicId)
            ->where('id', $doctorId)
            ->where('role', 'doctor')
            ->where('is_active', true)
            ->exists();
    }

    /**
     * إنشاء موعد جديد.
     */
    public function createAppointment(array $data): Appointment
    {
        return Appointment::create($data);
    }

    /**
     * تعديل بيانات موعد داخل العيادة فقط.
     */
    public function updateAppointment(
        string $id,
        string $clinicId,
        array $data
    ): Appointment {
        $appointment = Appointment::query()
            ->where('clinic_id', $clinicId)
            ->where('id', $id)
            ->firstOrFail();

        $appointment->update($data);

        return $appointment->refresh()->load(['patient', 'user']);
    }

    /**
     * تغيير حالة موعد داخل العيادة فقط.
     */
    public function updateAppointmentStatus(
        string $id,
        string $clinicId,
        string $status
    ): Appointment {
        $appointment = Appointment::query()
            ->where('clinic_id', $clinicId)
            ->where('id', $id)
            ->firstOrFail();

        $appointment->update([
            'status' => $status,
        ]);

        return $appointment->refresh()->load(['patient', 'user']);
    }
}
