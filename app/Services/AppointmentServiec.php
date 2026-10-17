<?php

namespace App\Services;

use App\Models\Appointment;
use App\Repositories\AppointmentRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;

class AppointmentService
{
    public function __construct(
        private AppointmentRepository $appointmentRepository
    ) {}


    public function getAllAppointments()
    {
        return $this->appointmentRepository->getAllAppointments(
            $this->currentClinicId()
        );
    }


    public function getAppointmentById(string $id): Appointment
    {
        return $this->appointmentRepository->getAppointmentById(
            $id,
            $this->currentClinicId()
        );
    }


    public function createAppointment(array $data): Appointment
    {
        $clinicId = $this->currentClinicId();

        if (! $this->appointmentRepository->patientBelongsToClinic(
            $data['patient_id'],
            $clinicId
        )) {
            throw ValidationException::withMessages([
                'patient_id' => 'المريض غير موجود داخل العيادة الحالية.',
            ]);
        }

        if (! $this->appointmentRepository->doctorBelongsToClinic(
            $data['doctor_id'],
            $clinicId
        )) {
            throw ValidationException::withMessages([
                'doctor_id' => 'الطبيب غير موجود أو غير نشط داخل العيادة الحالية.',
            ]);
        }


        $appointmentData = [
            'clinic_id' => $clinicId,
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'],
            'appointment_date' => $data['appointment_date'],
            'time_slot' => $data['time_slot'],
            'status' => $data['status'] ?? 'pending',
            'notes' => $data['notes'] ?? null,
        ];

        return $this->appointmentRepository->createAppointment(
            $appointmentData
        );
    }


    public function updateAppointment(
        string $id,
        array $data
    ): Appointment {
        $clinicId = $this->currentClinicId();


        $this->appointmentRepository->getAppointmentById(
            $id,
            $clinicId
        );


        if (isset($data['patient_id'])) {
            if (! $this->appointmentRepository->patientBelongsToClinic(
                $data['patient_id'],
                $clinicId
            )) {
                throw ValidationException::withMessages([
                    'patient_id' => 'المريض غير موجود داخل العيادة الحالية.',
                ]);
            }
        }

        if (isset($data['doctor_id'])) {
            if (! $this->appointmentRepository->doctorBelongsToClinic(
                $data['doctor_id'],
                $clinicId
            )) {
                throw ValidationException::withMessages([
                    'doctor_id' => 'الطبيب غير موجود أو غير نشط داخل العيادة الحالية.',
                ]);
            }
        }


        $allowedData = array_intersect_key(
            $data,
            array_flip([
                'patient_id',
                'doctor_id',
                'appointment_date',
                'time_slot',
                'notes',
            ])
        );

        if (empty($allowedData)) {
            throw ValidationException::withMessages([
                'appointment' => 'لم يتم إرسال بيانات صالحة للتعديل.',
            ]);
        }

        return $this->appointmentRepository->updateAppointment(
            $id,
            $clinicId,
            $allowedData
        );
    }

    public function cancelAppointment(string $id): Appointment
    {
        $clinicId = $this->currentClinicId();

        $appointment = $this->appointmentRepository->getAppointmentById(
            $id,
            $clinicId
        );

        if ($appointment->status === 'cancelled') {
            throw ValidationException::withMessages([
                'status' => 'الموعد ملغي بالفعل.',
            ]);
        }

        if ($appointment->status === 'attended') {
            throw ValidationException::withMessages([
                'status' => 'لا يمكن إلغاء موعد تم حضوره.',
            ]);
        }

        return $this->appointmentRepository->updateAppointmentStatus(
            $id,
            $clinicId,
            'cancelled'
        );
    }


    public function updateStatus(
        string $id,
        string $status
    ): Appointment {
        $clinicId = $this->currentClinicId();

        $allowedStatuses = [
            'pending',
            'confirmed',
            'attended',
            'cancelled',
            'no_show',
        ];

        if (! in_array($status, $allowedStatuses, true)) {
            throw ValidationException::withMessages([
                'status' => 'حالة الموعد غير صالحة.',
            ]);
        }

        $appointment = $this->appointmentRepository->getAppointmentById(
            $id,
            $clinicId
        );

        if ($appointment->status === 'cancelled') {
            throw ValidationException::withMessages([
                'status' => 'لا يمكن تغيير حالة موعد ملغي.',
            ]);
        }

        if ($appointment->status === 'attended' && $status !== 'attended') {
            throw ValidationException::withMessages([
                'status' => 'لا يمكن تغيير حالة موعد تم حضوره.',
            ]);
        }

        return $this->appointmentRepository->updateAppointmentStatus(
            $id,
            $clinicId,
            $status
        );
    }


    private function currentClinicId(): string
    {
        $clinicId = auth()->user()?->clinic_id;

        if (! $clinicId) {
            throw new AuthorizationException(
                'لا يوجد Clinic مرتبط بالمستخدم الحالي.'
            );
        }

        return $clinicId;
    }
}
