<?php

namespace App\Http\Requests\Permission;

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
            'name' => ['required', 'string', 'unique:permissions,name,NULL,id,deleted_at,NULL'],
            'description' => ['required', 'string'],
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
            'name.required' => 'The attribute :attribute of the category is required',
            'name.string' => 'The attribute :attribute of the category must be a string',
            'name.unique' => 'The attribute :attribute of the category has already been registered',
            'description.required' => 'The attribute :attribute of the category is required',
            'description.string' => 'The attribute :attribute of the category must be a string',
        ];
    }
}
