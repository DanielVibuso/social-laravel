<?php

namespace App\Http\Requests\Permission;

use App\Models\Permission;
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
            'name' => [
                'string',
                'unique:permissions,name,' . $this->route()->id . ',id,deleted_at,NULL',
                Rule::unique(Permission::class)->ignore($this->route()->id),
            ],
            'description' => ['string'],
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
            'name.string' => 'The attribute :attribute of the category must be a string',
            'name.unique' => 'The attribute :attribute of the category has already been registered',
            'description.string' => 'The attribute :attribute of the category must be a string',
        ];
    }
}
