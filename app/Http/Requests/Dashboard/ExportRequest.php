<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class ExportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'memberID'   => 'nullable|integer|min:0',
            'originID'   => 'nullable|integer|min:0',
            'categoryID' => 'nullable|integer|min:0',
            'year'       => 'nullable|digits:4',
            'month'      => 'nullable|integer|min:1|max:2',
        ];
    }

    public function messages(): array
    {
        return [
            'memberID.integer' => 'O campo Membro deve ser um número inteiro.',
            'memberID.min'     => 'O campo Membro deve ser no mínimo 0.',
            'originID.integer' => 'O campo Origem deve ser um número inteiro.',
            'originID.min'     => 'O campo Origem deve ser no mínimo 0.',
            'categoryID.integer' => 'O campo Categoria deve ser um número inteiro.',
            'categoryID.min'     => 'O campo Categoria deve ser no mínimo 0.',
            'year.digits'      => 'O campo Ano deve conter exatamente 4 dígitos.',
            'month.integer'    => 'O campo Mês deve ser um número inteiro.',
            'month.min'        => 'O campo Mês deve ser no mínimo 1.',
            'month.max'        => 'O campo Mês deve ser no máximo 2.',
        ];
    }
}
