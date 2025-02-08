<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'email' => ['string', 'email', Rule::unique(User::class)->ignore($this->route()->id)],
            'name' => ['string'],
            'password' => ['min:8', 'confirmed'],
            'password_confirmation' => ['required_with:password'],
            'profile_id' => ['sometimes', 'string', 'exists:App\Models\Profile,id'],
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
            'email.unique' => 'Email has already been registered',
            'password.min' => 'The password must be at least 8 characters long',
            'profile_id.string' => 'The profile id must be a string',
            'profile_id.exists' => 'The profile id was not found',
            'name.string' => 'The name must be a string',
            'password_confirmation.required_with' => 'The password confirmation field is required when a password is provided',
            'password.confirmed' => 'The password confirmation does not match the password',
        ];
    }
}
