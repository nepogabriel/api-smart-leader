<?php

namespace App\Http\Requests\Task;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string','max:60'],
            'description' => ['required', 'string','max:2000'],
            'status' => ['required', 'in:pending,in_progress,done'],
            'priority' => ['required', 'in:low,medium,high'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O campo título é obrigatório.',
            'title.max' => 'O título não pode ter mais de 60 caracteres.',

            'description.required' => 'O campo descrição é obrigatório.',
            'description.max' => 'A descrição não pode ter mais de 2000 caracteres.',

            'status.required' => 'O campo status é obrigatório.',
            'status.in' => 'O status deve ser: pending, in_progress ou done.',

            'priority.required' => 'O campo prioridade é obrigatório.',
            'priority.in' => 'A prioridade deve ser: low, medium ou high.',

            'due_date.required' => 'O campo prazo é obrigatório.',
            'due_date.date' => 'O prazo deve ser uma data válida.',
            'due_date.after_or_equal' => 'O prazo deve ser hoje ou uma data futura.',
        ];
    }

    public function attributes(): array
    {
        return [
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
            'title'    => $this->title ? trim($this->title) : null,
            'status'   => $this->status ? trim($this->status) : null,
            'priority' => $this->priority ? trim($this->priority) : null,
            'due_date' => $this->due_date,
        ]);
    }
}
