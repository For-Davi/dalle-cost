<?php

namespace App\Http\Requests\Movement;

use Illuminate\Foundation\Http\FormRequest;

class CreateMovementRequest extends FormRequest
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
            'quantity' => 'required|integer|min:1|max:12',
            'memberID' => 'nullable|exists:members,id',
            'originID' => 'nullable|exists:origins,id',
            'categoryID' => 'nullable|exists:origins,id',
            'description' => 'nullable|string',
            'date_buy' => [
                'required',
                'regex:/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/', // dd/mm/yyyy
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

            'quantity.required' => 'A quantidade é obrigatória',
            'quantity.integer'  => 'A quantidade deve ser um número inteiro',
            'quantity.min'      => 'A quantidade mínima permitida é 1',
            'quantity.max'      => 'A quantidade máxima permitida é 12',

            'memberID.exists'   => 'O membro selecionado não existe',

            'originID.exists'   => 'A origem selecionada não existe',

            'categoryID.exists'   => 'A categoria selecionada não existe',

            'description.string'   => 'A descrição deve ser um texto válido',

            'date_buy.required' => 'A data de compra é obrigatória',
            'date_buy.regex'    => 'A data de compra deve estar no formato dd/mm/yyyy (ex: 25/12/2024)',
        ];
    }
}
