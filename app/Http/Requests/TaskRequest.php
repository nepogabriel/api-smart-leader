<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'string','max:255'],
            'status' => ['nullable', 'in:pending,in_progress,done'],
            'priority' => ['nullable', 'in:low,medium,high'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'O usuário selecionado é inválido.',

            'title.required' => 'O título é obrigatório.',
            'title.max'      => 'O título não pode ter mais de 255 caracteres.',

            'status.in'      => 'O status deve ser pendente, em andamento ou concluído.',

            'priority.in'    => 'A prioridade deve ser baixa, média ou alta.',

            'due_date.date'  => 'O prazo deve ser uma data válida.',
            'due_date.after_or_equal' => 'O prazo deve ser hoje ou uma data futura.',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id'  => 'usuário',
            'title'    => 'título',
            'status'   => 'status',
            'priority' => 'prioridade',
            'due_date' => 'prazo',
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
            'user_id'  => $this->user_id ? (int) $this->user_id : null,
            'title'    => $this->title ? trim($this->title) : null,
            'status'   => $this->status ? trim($this->status) : null,
            'priority' => $this->priority ? trim($this->priority) : null,
            'due_date' => $this->due_date,
        ]);
    }
}
