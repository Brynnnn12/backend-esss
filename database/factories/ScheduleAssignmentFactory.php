<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScheduleAssignment>
 */
class ScheduleAssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'schedule_id' => \App\Models\Schedule::factory(),
            'user_id' => \App\Models\User::factory(),
            'shift_id' => \App\Models\Shift::factory(),
        ];
    }
}
