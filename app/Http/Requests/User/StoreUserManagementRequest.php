<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserManagementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],

            'role' => ['required', 'in:owner,doctor,receptionist'],

            // Doctor fields
            'specialty' => ['nullable', 'string', 'max:255'],
            'syndicate_card_number' => ['nullable', 'string', 'max:255'],

            'password' => ['required', 'string', 'min:8'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}