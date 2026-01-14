<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$userId],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$userId],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:administrator,engineer,client'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'username.unique' => 'Korisničko ime već postoji.',
            'email.unique' => 'Email adresa već postoji.',
            'password.confirmed' => 'Lozinke se ne poklapaju.',
        ];
    }
}
