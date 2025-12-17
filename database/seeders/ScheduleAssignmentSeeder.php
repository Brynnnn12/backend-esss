<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil schedules yang ada
        $schedules = \App\Models\Schedule::all();
        $shifts = \App\Models\Shift::all();
        $users = \App\Models\User::whereHas('roles', function ($query) {
            $query->where('name', 'Employee');
        })->get();

        if ($schedules->isEmpty() || $shifts->isEmpty() || $users->isEmpty()) {
            return; // Skip jika tidak ada data
        }

        // Assign random employee ke random schedule dengan random shift
        foreach ($schedules as $schedule) {
            $randomUsers = $users->random(min(3, $users->count())); // Max 3 per schedule
            foreach ($randomUsers as $user) {
                \App\Models\ScheduleAssignment::factory()->create([
                    'schedule_id' => $schedule->id,
                    'user_id' => $user->id,
                    'shift_id' => $shifts->random()->id,
                ]);
            }
        }
    }
}
