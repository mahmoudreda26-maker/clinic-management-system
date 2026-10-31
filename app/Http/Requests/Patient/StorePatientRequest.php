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
            'name' => ['required', 'string', 'min:3', 'max:255'],
            
            'address' => ['nullable', 'string', 'max:255'],

            'phone' => ['required', 'string', 'max:20'],

            'national_id' => ['nullable', 'string', 'max:14'],

            'clinic_id' => ['prohibited'],
        ];
    }
}
