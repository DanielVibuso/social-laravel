<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'unique:users'],
            'name' => ['required', 'string'],
            'password' => ['required', 'min:8'],
            'profile_id' => ['required', 'string', 'exists:App\Models\Profile,id'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.string' => 'The name must be a string',
            'email.required' => 'Email is required',
            'email.unique' => 'Email has already been registered',
            'password.required' => 'A password is required for the user',
            'password.min' => 'The password must be at least 8 characters long',
        ];
    }
}
