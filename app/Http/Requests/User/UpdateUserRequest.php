<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|exists:users,id',
            'name' => 'required|string|min:3|max:30',
            'email' => 'required|string|email|max:50|unique:users,email,'.$this->id,
            'type' => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'O ID do usuário é obrigatório.',
            'id.exists' => 'O usuário selecionado não existe.',
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome deve ter no máximo 30 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.string' => 'O e-mail deve ser uma string.',
            'email.email' => 'O e-mail deve ser um endereço de e-mail válido.',
            'email.max' => 'O e-mail deve ter no máximo 50 caracteres.',
            'email.unique' => 'Este e-mail já está em uso por outro usuário.',
            'type.required' => 'O tipo de acesso é obrigatório.',
            'type.in' => 'O tipo de acesso selecionado não é válido.',
        ];
    }
}