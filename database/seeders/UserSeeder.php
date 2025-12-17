<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create HR user
        $hr = \App\Models\User::factory()->create([
            'name' => 'HR Admin',
            'email' => 'hr@example.com',
        ]);
        $hr->assignRole('Hr');

        // Create Employee users
        $employees = \App\Models\User::factory()->count(10)->create();
        foreach ($employees as $employee) {
            $employee->assignRole('Employee');
        }
    }
}
