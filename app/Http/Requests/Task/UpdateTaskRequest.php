<?php

namespace App\Http\Requests\Task;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['sometimes', 'nullable', 'string', 'max:60'],
            'description' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'status'      => ['sometimes', 'nullable', 'in:pending,in_progress,done'],
            'priority'    => ['sometimes', 'nullable', 'in:low,medium,high'],
            'due_date'    => ['sometimes', 'nullable', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'O título deve ser um texto válido.',
            'title.max' => 'O título não pode ter mais de 60 caracteres.',

            'description.string' => 'A descrição deve ser um texto válido.',
            'description.max' => 'A descrição não pode ter mais de 2000 caracteres.',

            'status.in' => 'O status deve ser: pending, in_progress ou done.',

            'priority.in' => 'A prioridade deve ser: low, medium ou high.',

            'due_date.date' => 'O prazo deve ser uma data válida.',
            'due_date.after_or_equal' => 'O prazo deve ser hoje ou uma data futura.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title'       => 'título',
            'description' => 'descrição',
            'status'      => 'status',
            'priority'    => 'prioridade',
            'due_date'    => 'prazo',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Dados inválidos.',
                'errors'  => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title'       => $this->title ? trim($this->title) : null,
            'description' => $this->description ? trim($this->description) : null,
            'status'      => $this->status ? trim($this->status) : null,
            'priority'    => $this->priority ? trim($this->priority) : null,
            'due_date'    => $this->due_date,
        ]);
    }
}
