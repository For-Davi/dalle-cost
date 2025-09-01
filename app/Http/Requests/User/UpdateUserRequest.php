<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('userID');
        
        return [
            'name' => 'required|string|min:3|max:30',
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique('users')->ignore($userId)
            ],
            'role' => 'required|in:viewer,admin',
            'user' => 'sometimes|exists:users,id'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome deve ter no máximo 30 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.string' => 'O e-mail deve ser uma string.',
            'email.email' => 'O e-mail deve ser um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso por outro usuário.',
            'role.required' => 'O tipo de acesso é obrigatório.',
            'role.in' => 'O tipo de acesso selecionado não é válido.',
            'user.exists' => 'O usuário selecionado não existe.'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user' => $this->route('userID')
        ]);
    }
}