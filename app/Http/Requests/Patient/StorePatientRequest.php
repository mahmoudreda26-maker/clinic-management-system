<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'min:3', 'max:255'],

            'gender' => ['required', Rule::in(['male', 'female'])],

            'address' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['required', 'string', 'max:20'],

            'national_id' => ['nullable', 'string', 'max:30'],

            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],

            'blood_type' => [
                'nullable',
                Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            ],

            'chronic_diseases' => ['nullable', 'string', 'max:5000'],

            'clinic_id' => ['prohibited'],
        ];
    }
}
