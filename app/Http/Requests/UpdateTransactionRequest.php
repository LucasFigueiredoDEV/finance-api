<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTransactionRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'sometimes|required|numeric|gt:0',
            'description' => 'sometimes|nullable|string',
            'date' => 'sometimes|required|date|before_or_equal:today',
            'type' => 'sometimes|required|in:income,expense',
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')
                    ->where(fn ($query) =>
                        $query->where('type', $this->input('type'))
                    ),
            ],
        ];
    }

    /**
     * Get custom error messages for validation failures.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'amount.gt' => 'The amount must be greater than zero.',
            'type.in' => 'The type must be either income or expense.',
            'date.before_or_equal' => 'The date must be today or a past date.',
        ];
    }
}
