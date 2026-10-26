<?php

namespace App\Http\Requests\Role;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->tokenCan('SYSTEM:MANAGE_ROLES');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $roleId = $this->route('id');
        return [
            'name' => ['sometimes','required','string','min:3','max:255',
            Rule::unique('role', 'name')->ignore($roleId)],
            'description' => 'sometimes|nullable|string',
            'role_type_id' => 'sometimes|required|exists:role_type,id',
            'permission' => 'sometimes|required|array',
            'permission.*' => 'sometimes|required|integer|exists:permission,id|distinct',
        ];
    }
}
