<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\User;

class TaskRepository
{
    public function register(array $user): Task
    {
        $task = Task::create($user);

        if (!$task)
            throw new \Exception('Ops! Algo de errado ocorreu ao registar a tarefa.');

        return $task;
    }
}