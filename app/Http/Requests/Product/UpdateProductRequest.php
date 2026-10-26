<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->tokenCan('SYSTEM:MANAGE_PRODUCTS');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes','required','string','max:255',
            'description' => ['sometimes','nullable', 'string', 'max:1000', new \App\Rules\BadWordCheck()],
            'price' => 'sometimes|required|numeric|min:1',
            'quantity' => 'sometimes|required|integer|min:1',
            'category' => 'sometimes|required|array',
            'category.*' => ['sometimes', 'required','integer','exists:category,id','distinct'],
        ];
    }
}
