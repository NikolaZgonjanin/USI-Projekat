<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupportRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Klijenti i inženjeri mogu da kreiraju prijave
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'firmware_version_id' => ['required', 'exists:firmware_versions,id'],
            'title' => ['required', 'string', 'max:255'],
            'request_text' => ['required', 'string'],
            'steps_to_reproduce' => ['nullable', 'string'],
        ];
    }
}
