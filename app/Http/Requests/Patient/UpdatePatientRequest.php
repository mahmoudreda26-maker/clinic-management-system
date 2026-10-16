<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'full_name' => ['sometimes', 'required', 'string', 'min:3', 'max:255'],

            'gender' => ['sometimes', 'required', Rule::in(['male', 'female'])],

            'address' => ['sometimes', 'nullable', 'string', 'max:255'],

            'phone' => ['sometimes', 'required', 'string', 'max:20'],

            'national_id' => ['sometimes', 'nullable', 'string', 'max:30'],

            'date_of_birth' => [
                'sometimes',
                'nullable',
                'date',
                'before_or_equal:today',
            ],

            'blood_type' => [
                'sometimes',
                'nullable',
                Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            ],

            'chronic_diseases' => ['sometimes', 'nullable', 'string', 'max:5000'],

            'clinic_id' => ['prohibited'],
        ];
    }
}