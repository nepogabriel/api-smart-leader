<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class TaskRepository
{
    public function register(array $user): Task
    {
        $task = Task::create($user);

        if (!$task)
            throw new \Exception('Ops! Algo de errado ocorreu ao registar a tarefa.');

        return $task;
    }

    public function getTaskById(int $id): Task
    {
        $task = Task::find($id);

        if (!$task)
            throw new \Exception('Tarefa não encontrada', Response::HTTP_NOT_FOUND);

        return $task;
    }
}