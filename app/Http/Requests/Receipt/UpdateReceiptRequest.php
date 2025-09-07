<?php

namespace App\Http\Requests\Receipt;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receipt' => 'sometimes|exists:receipts,id',
            'value' => 'required|numeric|min:0.01',
            'period' => [
                'required',
                'regex:/^(0[1-9]|1[0-2])\/\d{4}$/', // mm/yyyy
            ],
            'dateReceipt' => [
                'required',
                'regex:/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/', // dd/mm/yyyy
            ],
            'memberID' => 'nullable|exists:members,id',
        ];
    }

    public function messages(): array
    {
        return [
            'receipt.exists'   => 'O recimento selecionado não existe.',
            'value.required'    => 'O valor é obrigatório.',
            'value.numeric'     => 'O valor deve ser numérico.',
            'value.min'         => 'O valor deve ser maior que zero.',
            'period.required'   => 'O período é obrigatório.',
            'period.regex'      => 'O período deve estar no formato mm/yyyy (ex: 03/2024).',
            'memberID.exists'   => 'O membro selecionado não existe.',
            'dateReceipt.required' => 'A data de recebimento é obrigatória',
            'dateReceipt.regex'    => 'A data de recebimento deve estar no formato dd/mm/yyyy (ex: 25/12/2024)',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'receipt' => $this->route('receiptID'),
        ]);
    }
}
