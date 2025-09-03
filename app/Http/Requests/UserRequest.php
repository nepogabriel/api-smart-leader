<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => [
                'required',
                'integer',
                'exists:companies,id',
            ],
            'name' => [
                'required',
                'string',
                'min:3',
                'max:80',
                'regex:/^[a-zA-ZÀ-ÿ\s]+$/',
            ],
            'email' => [
                'required',
                'email:rfc',
                'unique:users,email',
                'max:255',

            ],
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:8',
            ],
            'password_confirmation' => [
                'required'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'O ID da empresa é obrigatório.',
            'company_id.integer' => 'O ID da empresa deve ser um número inteiro.',
            'company_id.exists' => 'A empresa selecionada não existe.',

            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto válido.',
            'name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'name.max' => 'O nome não pode ter mais de 80 caracteres.',
            'name.regex' => 'O nome deve conter apenas letras e espaços.',

            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ter um formato válido.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'email.max' => 'O e-mail não pode ter mais de 255 caracteres.',

            'password.required' => 'A senha é obrigatório.',
            'password.string' => 'A senha deve ser um texto válido.',
            'password.confirmed' => 'As senhas não são iguais.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',

            'password_confirmation.required' => 'O campo de confirmação da senha é obrigatório.'
        ];
    }

    public function attributes(): array
    {
        return [
            'company_id' => 'empresa',
            'name'       => 'nome',
            'email'      => 'e-mail',
            'password'   => 'senha'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Dados inválidos.',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'company'  => (int) $this->company_id,
            'name'     => trim($this->name),
            'email'    => strtolower(trim($this->email)),
            'password' => trim($this->password)
        ]);
    }
}
