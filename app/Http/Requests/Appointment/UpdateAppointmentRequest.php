<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAppointmentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [

            'patient_id' => ['sometimes', 'required', 'uuid'],

            'doctor_id' => ['sometimes', 'required', 'uuid'],

            'appointment_date' => ['sometimes', 'nullable', 'date', 'after_or_equal:today'],

            'time_slot' => ['sometimes', 'nullable', 'string', 'max:50'],

            'status' => ['sometimes', 'nullable', Rule::in(['pending', 'confirmed', 'attended', 'cancelled', 'no_show',])],

            'notes' => ['sometimes', 'nullable', 'string', 'max:2000'],
        ];
    }
}
