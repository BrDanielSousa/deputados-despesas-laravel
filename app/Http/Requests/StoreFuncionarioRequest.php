<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFuncionarioRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:funcionarios,email',
            'cpf' => 'required|string|size:11|unique:funcionarios,cpf',
            'cargo' => 'nullable|string|max:255',
            'dataAdmissao' => 'nullable|date',
            'salario' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.string' => 'O nome deve ser uma string válida.',
            'nome.max' => 'O nome não pode ter mais que 255 caracteres.',

            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser um endereço válido.',
            'email.unique' => 'Este e-mail já está em uso.',

            'cpf.required' => 'O campo CPF é obrigatório.',
            'cpf.string' => 'O CPF deve ser uma sequência de caracteres válida.',
            'cpf.size' => 'O CPF deve conter exatamente 11 caracteres.',
            'cpf.unique' => 'Este CPF já está cadastrado.',

            'cargo.string' => 'O cargo deve ser uma string válida.',
            'cargo.max' => 'O cargo não pode ter mais que 255 caracteres.',

            'dataAdmissao.date' => 'A data de admissão deve ser uma data válida.',

            'salario.numeric' => 'O salário deve ser um número.',
            'salario.min' => 'O salário deve ser maior ou igual a zero.',
        ];
    }
}
