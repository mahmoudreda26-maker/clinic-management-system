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
        'name' => ['sometimes', 'required', 'string', 'min:3', 'max:255'],

        'phone' => ['sometimes', 'nullable', 'string', 'max:20'],

        'national_id' => [
            'sometimes',
            'nullable',
            'string',
            'size:14',
            Rule::unique('patients', 'national_id')
                ->ignore($this->route('patientId'), 'id'),
        ],

        'clinic_id' => ['prohibited'],
    ];
}
}
