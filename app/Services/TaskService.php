<?php

namespace App\Services;

use App\Jobs\SendTaskEmailJob;
use App\Repositories\TaskRepository;
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

            if ($task) {
                SendTaskEmailJob::dispatch(auth()->user(), $task);
            }
        
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

    public function getTaskById(int $id): array
    {
        try {
            $task = $this->taskRepository->getTaskById($id);
        
            return [
                'return' => [
                    'message' => 'Tarefa encontrada com sucesso!',
                    'data' => $task,
                ],
                'code' => Response::HTTP_OK
            ];
        } catch (\Exception $exception) {
            Log::error('Erro ao buscar tarefa por ID: ', [
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

    public function updateTaskById(int $id, array $data,): array
    {
        try {
            $task = $this->taskRepository->getTaskById($id);

            $updated = $this->taskRepository->updateTask($task, $data);
        
            return [
                'return' => [
                    'message' => $updated ? 'Tarefa atualizada com sucesso!' : 'Não foi possível atualizar a tarefa',
                ],
                'code' => Response::HTTP_OK
            ];
        } catch (\Exception $exception) {
            Log::error('Erro ao atualizar tarefa: ', [
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

    public function deleteTaskById(int $id): array
    {
        try {
            $task = $this->taskRepository->getTaskById($id);

            $deleted = $this->taskRepository->deleteTask($task);
        
            return [
                'return' => [
                    'message' => $deleted ?? 'Tarefa deletada com sucesso!',
                ],
                'code' => Response::HTTP_OK
            ];
        } catch (\Exception $exception) {
            Log::error('Erro ao deletar tarefa: ', [
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