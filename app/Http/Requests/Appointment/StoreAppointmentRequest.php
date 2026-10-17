<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'patient_id'       => ['required', 'uuid'],

            'doctor_id'        => ['required', 'uuid'],

            'appointment_date' => ['required', 'date', 'after_or_equal:today'],

            'time_slot'        => ['required', 'string', 'max:50'],

            'status'           => ['sometimes', 'required', Rule::in(['pending', 'confirmed', 'attended', 'cancelled', 'no_show'])],
            
            'notes'            => ['nullable', 'string', 'max:2000'],
        ];
    }
}
