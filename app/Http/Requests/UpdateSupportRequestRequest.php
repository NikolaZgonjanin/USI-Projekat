<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupportRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Inženjeri mogu da menjaju status i assigned_to, autor može da menja svoje prijave
        $user = $this->user();
        $supportRequest = $this->route('support_request');

        if ($user->isEngineer() || $user->isAdmin()) {
            return true;
        }

        return $supportRequest->created_by === $user->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'request_text' => ['required', 'string'],
            'steps_to_reproduce' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:pending,accepted,denied,closed'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ];
    }
}
