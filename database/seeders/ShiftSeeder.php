<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shift::factory()->count(10)->create();
    }
}
