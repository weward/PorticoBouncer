<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $title = (request()->isMethod('PUT')) ? 'required|string' : 'required|string|unique:roles,title';

        return [
            'title' => $title,
            'abilities' => 'required|array',
            // 'abilities.*' => 'integer',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a name for the role',
            'abilities.required' => 'Please add an ability',
        ];
    }
}
