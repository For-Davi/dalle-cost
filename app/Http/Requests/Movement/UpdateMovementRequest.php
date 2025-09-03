<?php

namespace App\Http\Requests\Movement;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'movement' => 'sometimes|exists:movements,id',
            'value' => 'required|numeric|min:0.01',
            'period' => [
                'required',
                'regex:/^(0[1-9]|1[0-2])\/\d{4}$/', // mm/yyyy
            ],
            'dateBuy' => [
                'required',
                'regex:/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/', // dd/mm/yyyy
            ],
            'memberID' => 'nullable|exists:members,id',
            'originID' => 'nullable|exists:origins,id',
            'categoryID' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'movement.exists'   => 'O movimento selecionado não existe.',

            'value.required'    => 'O valor é obrigatório.',
            'value.numeric'     => 'O valor deve ser numérico.',
            'value.min'         => 'O valor deve ser maior que zero.',

            'period.required'   => 'O período é obrigatório.',
            'period.regex'      => 'O período deve estar no formato mm/yyyy (ex: 03/2024).',

            'memberID.exists'   => 'O membro selecionado não existe.',

            'originID.exists'   => 'A origem selecionada não existe.',

            'categoryID.exists'   => 'A categoria selecionada não existe.',

            'dateBuy.required' => 'A data de compra é obrigatória',
            'dateBuy.regex'    => 'A data de compra deve estar no formato dd/mm/yyyy (ex: 25/12/2024)',

            'description.string' => 'A descrição deve ser um texto válido.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'movement' => $this->route('dataID'),
        ]);
    }
}
