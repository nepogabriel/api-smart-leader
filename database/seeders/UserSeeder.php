<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();

        if ($companies->count() > 0) {
            foreach (range(1, 10) as $i) {
                User::factory()->create([
                    'company_id' => $companies->random()->id
                ]);
            }
        }
    }
}
