<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Services\TaskService;
use App\Repositories\TaskRepository;
use App\Jobs\SendTaskEmailJob;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    protected $taskRepository;
    protected $taskService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->taskRepository = $this->createMock(TaskRepository::class);
        $this->taskService = new TaskService($this->taskRepository);

        Queue::fake();

        Auth::shouldReceive('user')->andReturn((object) ['id' => 1, 'name' => 'Test User']);
    }

    public function test_register_success()
    {
        $taskData = [
            'title' => 'Nova Tarefa',
            'description' => 'Descrição da tarefa',
            'status' => 'pending',
            'priority' => 'medium',
            'due_date' => '2025-09-30T00:00:00.000000Z',
        ];

        $task = $this->createMock(Task::class);
        $task->method('getAttribute')->willReturnMap([
            ['title', $taskData['title']],
            ['description', $taskData['description']],
            ['status', $taskData['status']],
            ['priority', $taskData['priority']],
            ['due_date', $taskData['due_date']],
        ]);

        $this->taskRepository
            ->expects($this->once())
            ->method('register')
            ->with($taskData)
            ->willReturn($task);

        $result = $this->taskService->register($taskData);

        $this->assertEquals([
            'return' => [
                'message' => 'Tarefa Cadastrada com sucesso!',
                'data' => $task,
            ],
            'code' => Response::HTTP_CREATED,
        ], $result);

        Queue::assertPushed(SendTaskEmailJob::class, function ($job) {
            return $job->user->id === 1 && $job->task->getAttribute('title') === 'Nova Tarefa';
        });
    }

    public function test_register_throws_exception()
    {
        $taskData = [
            'title' => 'Nova Tarefa',
            'description' => 'Descrição da tarefa',
        ];

        $this->taskRepository
            ->expects($this->once())
            ->method('register')
            ->with($taskData)
            ->willThrowException(new \Exception('Erro ao cadastrar', Response::HTTP_BAD_REQUEST));

        $result = $this->taskService->register($taskData);

        $this->assertEquals([
            'return' => [
                'error' => 'Erro ao cadastrar',
            ],
            'code' => Response::HTTP_BAD_REQUEST,
        ], $result);

        Queue::assertNotPushed(SendTaskEmailJob::class);
    }

    public function test_get_task_by_id_success()
    {
        $taskId = 1;

        $task = $this->createMock(Task::class);
        $task->method('getAttribute')->willReturnMap([
            ['id', $taskId],
            ['title', 'Tarefa Teste'],
            ['status', 'pending'],
        ]);
        $this->taskRepository
            ->expects($this->once())
            ->method('getTaskById')
            ->with($taskId)
            ->willReturn($task);

        $result = $this->taskService->getTaskById($taskId);

        $this->assertEquals([
            'return' => [
                'message' => 'Tarefa encontrada com sucesso!',
                'data' => $task,
            ],
            'code' => Response::HTTP_OK,
        ], $result);
    }

    public function test_get_task_by_id_throws_exception()
    {
        $taskId = 1;

        $this->taskRepository
            ->expects($this->once())
            ->method('getTaskById')
            ->with($taskId)
            ->willThrowException(new \Exception('Tarefa não encontrada', Response::HTTP_NOT_FOUND));

        $result = $this->taskService->getTaskById($taskId);

        $this->assertEquals([
            'return' => [
                'error' => 'Tarefa não encontrada',
            ],
            'code' => Response::HTTP_NOT_FOUND,
        ], $result);
    }

    public function test_update_task_by_id_success()
    {
        $taskId = 1;
        $taskData = [
            'title' => 'Tarefa Atualizada',
            'status' => 'in_progress',
        ];

        $task = $this->createMock(Task::class);
        $task->method('getAttribute')->willReturnMap([
            ['id', $taskId],
            ['title', 'Tarefa Antiga'],
        ]);

        $this->taskRepository
            ->expects($this->once())
            ->method('getTaskById')
            ->with($taskId)
            ->willReturn($task);

        $this->taskRepository
            ->expects($this->once())
            ->method('updateTask')
            ->with($task, $taskData)
            ->willReturn(true);

        $result = $this->taskService->updateTaskById($taskId, $taskData);

        $this->assertEquals([
            'return' => [
                'message' => 'Tarefa atualizada com sucesso!',
            ],
            'code' => Response::HTTP_OK,
        ], $result);
    }

    public function test_update_task_by_id_fails()
    {
        $taskId = 1;
        $taskData = [
            'title' => 'Tarefa Atualizada',
        ];

        $task = $this->createMock(Task::class);
        $task->method('getAttribute')->willReturnMap([
            ['id', $taskId],
            ['title', 'Tarefa Antiga'],
        ]);

        $this->taskRepository
            ->expects($this->once())
            ->method('getTaskById')
            ->with($taskId)
            ->willReturn($task);

        $this->taskRepository
            ->expects($this->once())
            ->method('updateTask')
            ->with($task, $taskData)
            ->willReturn(false);

        $result = $this->taskService->updateTaskById($taskId, $taskData);

        $this->assertEquals([
            'return' => [
                'message' => 'Não foi possível atualizar a tarefa',
            ],
            'code' => Response::HTTP_OK,
        ], $result);
    }

    public function test_update_task_by_id_throws_exception()
    {
        $taskId = 1;
        $taskData = [
            'title' => 'Tarefa Atualizada',
        ];

        $this->taskRepository
            ->expects($this->once())
            ->method('getTaskById')
            ->with($taskId)
            ->willThrowException(new \Exception('Tarefa não encontrada', Response::HTTP_NOT_FOUND));

        $result = $this->taskService->updateTaskById($taskId, $taskData);

        $this->assertEquals([
            'return' => [
                'error' => 'Tarefa não encontrada',
            ],
            'code' => Response::HTTP_NOT_FOUND,
        ], $result);
    }

    public function test_delete_task_by_id_success()
    {
        $taskId = 1;

        $task = $this->createMock(Task::class);
        $task->method('getAttribute')->willReturnMap([
            ['id', $taskId],
            ['title', 'Tarefa Teste'],
        ]);

        $this->taskRepository
            ->expects($this->once())
            ->method('getTaskById')
            ->with($taskId)
            ->willReturn($task);

        $this->taskRepository
            ->expects($this->once())
            ->method('deleteTask')
            ->with($task)
            ->willReturn(true);

        $result = $this->taskService->deleteTaskById($taskId);

        $this->assertEquals([
            'return' => [
                'message' => 'Tarefa deletada com sucesso!',
            ],
            'code' => Response::HTTP_OK,
        ], $result);
    }

    public function test_delete_task_by_id_throws_exception()
    {
        $taskId = 1;

        $this->taskRepository
            ->expects($this->once())
            ->method('getTaskById')
            ->with($taskId)
            ->willThrowException(new \Exception('Tarefa não encontrada', Response::HTTP_NOT_FOUND));

        $result = $this->taskService->deleteTaskById($taskId);

        $this->assertEquals([
            'return' => [
                'error' => 'Tarefa não encontrada',
            ],
            'code' => Response::HTTP_NOT_FOUND,
        ], $result);
    }
}
