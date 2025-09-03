<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function __construct(
        private TaskService $taskService
    ) {}

    public function index()
    {
        return Task::paginate(15);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $data = $this->taskService->register($request->validated());

        return ApiResponse::response($data['return'], $data['code']);
    }

    public function show(int $id)
    {
        $data = $this->taskService->getTaskById($id);

        return ApiResponse::response($data['return'], $data['code']);
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Tarefa não encontrada'], 404);
        }

        $data = array_filter($request->validated(), function ($value) {
            return !is_null($value);
        });

        $task->update($data);

        return response()->json([
            'message' => 'Tarefa atualizada com sucesso!',
            'data'    => $task
        ]);
    }

    public function destroy($id)
    {
        $data = $this->taskService->deleteTaskById($id);

        return ApiResponse::response($data['return'], $data['code']);
    }
}
