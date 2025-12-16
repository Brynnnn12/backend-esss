<?php

namespace Database\Factories;

use Illuminate\Support\Str;


use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            //buat nama department yang realistis seperti it, hr, finance, marketing, dll
            'name' => $this->faker->unique()->randomElement(['IT', 'HR', 'Finance', 'Marketing', 'Sales', 'Operations', 'Customer Service', 'R&D', 'Legal', 'Administration']),
        ];
    }
}
