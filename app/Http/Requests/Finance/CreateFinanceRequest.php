<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class CreateFinanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => 'required|numeric|min:0.01',
            'period' => [
                'required',
                'regex:/^(0[1-9]|1[0-2])\/\d{4}$/', // mm/yyyy
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'value.required'   => 'O valor é obrigatório',
            'value.numeric'    => 'O valor deve ser numérico',
            'value.min'        => 'O valor deve ser maior que zero',
            'period.required'  => 'O período é obrigatório',
            'period.regex'     => 'O período deve estar no formato mm/yyyy (ex: 03/2024)',
        ];
    }
}
