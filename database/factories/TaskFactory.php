<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        $statuses = [Task::STATUS_PENDING, Task::STATUS_IN_PROGRESS, Task::STATUS_DONE];
        $priorities = [Task::PRIORITY_LOW, Task::PRIORITY_MEDIUM, Task::PRIORITY_HIGH];
        
        return [
            'company_id' => Company::inRandomOrder()->first()->id ?? Company::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement($statuses),
            'priority' => $this->faker->randomElement($priorities),
            'due_date' => $this->faker->dateTimeBetween('now', '+30 days'),
        ];
    }
}
