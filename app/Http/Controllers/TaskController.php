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

    public function show(int $id): JsonResponse
    {
        $data = $this->taskService->getTaskById($id);

        return ApiResponse::response($data['return'], $data['code']);
    }

    public function update(UpdateTaskRequest $request, $id): JsonResponse
    {
        $data = array_filter($request->validated(), function ($value) {
            return !is_null($value);
        });

        $data = $this->taskService->updateTaskById($id, $data);

        return ApiResponse::response($data['return'], $data['code']);
    }

    public function destroy($id): JsonResponse
    {
        $data = $this->taskService->deleteTaskById($id);

        return ApiResponse::response($data['return'], $data['code']);
    }
}
