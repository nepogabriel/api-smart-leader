<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TaskService {
    public function __construct(
        private TaskRepository $taskRepository
    ) {}

    public function register(array $task): array
    {
        try {
            $task = $this->taskRepository->register($task);
        
            return [
                'return' => [
                    'message' => 'Tarefa Cadastrada com sucesso!',
                    'data' => $task,
                ],
                'code' => Response::HTTP_CREATED
            ];
        } catch (\Exception $exception) {
            Log::error('Erro ao cadastrar tarefa: ', [
                'message' => $exception->getMessage(),
                'code-http' => $exception->getCode()
            ]);

            return [
                'return' => [
                    'error' => $exception->getMessage(),
                ],
                'code' => $exception->getCode(),
            ];
        }
    }
}