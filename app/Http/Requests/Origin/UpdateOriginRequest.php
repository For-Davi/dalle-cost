<?php

namespace App\Http\Requests\Origin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOriginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:30',
            'memberID' => 'nullable|exists:members,id',
            'payday' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome deve ter no máximo 30 caracteres.',
            'memberID.exists' => 'A origem selecionada não existe.',
            'payday.required' => 'A data de pagamento é obrigatória',
            'payday.string' => 'O data de pagamento deve ser uma string.',
        ];
    }
}
