<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();
        
        if ($companies->count() > 0) {
            foreach (range(1, 10) as $i) {
                $company = $companies->random()->id;

                Task::factory()->create([
                    'company_id' => $company,
                ]);
            }
        }
    }
}
