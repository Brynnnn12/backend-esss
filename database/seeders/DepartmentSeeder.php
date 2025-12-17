<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = ['IT', 'HR', 'Finance', 'Marketing', 'Sales'];

        foreach ($departments as $name) {
            \App\Models\Department::factory()->create(['name' => $name]);
        }
    }
}
