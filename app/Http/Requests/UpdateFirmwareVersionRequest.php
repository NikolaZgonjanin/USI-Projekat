<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFirmwareVersionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isEngineer() || $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'project_id' => ['required', 'exists:projects,id'],
            'version' => ['required', 'string', 'max:255'],
            'is_stable' => ['boolean'],
            'changelog' => ['nullable', 'string'],
            'file_path' => ['nullable', 'string', 'max:255'],
            'released_at' => ['nullable', 'date'],
        ];
    }
}
