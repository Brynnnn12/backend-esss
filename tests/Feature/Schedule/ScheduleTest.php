<?php

use Spatie\Permission\Models\Role;


uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'Hr']);
    Role::create(['name' => 'Employee']);
});

test('Hr dapat melihat semua schedules', function () {
    //Arrange
    \App\Models\User::factory()->create()->assignRole('Hr');
    \App\Models\Schedule::factory()->count(3)->create();

    //Act
    $response = $this->actingAs(\App\Models\User::first())->getJson('/api/v1/schedules');

    //Assert
    $response->assertStatus(200);
    $response->assertJsonCount(3, 'data');
});

test('Employee tidak dapat melihat semua schedules', function () {
    //Arrange
    \App\Models\User::factory()->create()->assignRole('Employee');
    \App\Models\Schedule::factory()->count(3)->create();

    //Act
    $response = $this->actingAs(\App\Models\User::first())->getJson('/api/v1/schedules');

    //Assert
    $response->assertStatus(403);
});

test('Hr dapat membuat schedule', function () {
    //Arrange
    \App\Models\User::factory()->create()->assignRole('Hr');
    $department = \App\Models\Department::factory()->create();

    $data = [
        'department_id' => $department->id,
        'date' => '2024-12-01',
    ];

    //Act
    $response = $this->actingAs(\App\Models\User::first())->postJson('/api/v1/schedules', $data);

    //Assert
    $response->assertStatus(201);

    $this->assertDatabaseHas('schedules', [
        'department_id' => $department->id,
        'date' => '2024-12-01 00:00:00',
    ]);
});

test('Employee tidak dapat membuat schedule', function () {
    //Arrange
    \App\Models\User::factory()->create()->assignRole('Employee');
    $department = \App\Models\Department::factory()->create();

    $data = [
        'department_id' => $department->id,
        'date' => '2024-12-01',
    ];

    //Act
    $response = $this->actingAs(\App\Models\User::first())->postJson('/api/v1/schedules', $data);

    //Assert
    $response->assertStatus(403);
});

test('Hr dapat mengupdate schedule', function () {
    //Arrange
    \App\Models\User::factory()->create()->assignRole('Hr');
    $schedule = \App\Models\Schedule::factory()->create();
    $newDepartment = \App\Models\Department::factory()->create();

    $data = [
        'department_id' => $newDepartment->id,
        'date' => '2024-12-15',
    ];

    //Act
    $response = $this->actingAs(\App\Models\User::first())->putJson("/api/v1/schedules/{$schedule->id}", $data);

    //Assert
    $response->assertStatus(200);

    $this->assertDatabaseHas('schedules', [
        'id' => $schedule->id,
        'department_id' => $newDepartment->id,
        'date' => '2024-12-15 00:00:00',
    ]);
});

test('Employee tidak dapat mengupdate schedule', function () {
    //Arrange
    \App\Models\User::factory()->create()->assignRole('Employee');
    $schedule = \App\Models\Schedule::factory()->create();
    $newDepartment = \App\Models\Department::factory()->create();

    $data = [
        'department_id' => $newDepartment->id,
        'date' => '2024-12-15',
    ];

    //Act
    $response = $this->actingAs(\App\Models\User::first())->putJson("/api/v1/schedules/{$schedule->id}", $data);

    //Assert
    $response->assertStatus(403);
});

test('Hr dapat menghapus schedule', function () {
    //Arrange
    \App\Models\User::factory()->create()->assignRole('Hr');
    $schedule = \App\Models\Schedule::factory()->create();

    //Act
    $response = $this->actingAs(\App\Models\User::first())->deleteJson("/api/v1/schedules/{$schedule->id}");

    //Assert
    $response->assertStatus(200);

    $this->assertDatabaseMissing('schedules', [
        'id' => $schedule->id,
    ]);
});

test('Employee tidak dapat menghapus schedule', function () {
    //Arrange
    \App\Models\User::factory()->create()->assignRole('Employee');
    $schedule = \App\Models\Schedule::factory()->create();

    //Act
    $response = $this->actingAs(\App\Models\User::first())->deleteJson("/api/v1/schedules/{$schedule->id}");

    //Assert
    $response->assertStatus(403);
});

test('Hr dapat assign employee ke schedule', function () {
    //Arrange
    \App\Models\User::factory()->create()->assignRole('Hr');
    $schedule = \App\Models\Schedule::factory()->create();
    $user = \App\Models\User::factory()->create();
    $shift = \App\Models\Shift::factory()->create();

    $data = [
        'user_id' => $user->id,
        'shift_id' => $shift->id,
    ];

    //Act
    $response = $this->actingAs(\App\Models\User::first())->postJson("/api/v1/schedules/{$schedule->id}/assign", $data);

    //Assert
    $response->assertStatus(201);
    $response->assertJson([
        'success' => true,
        'message' => 'Employee assigned to schedule successfully',
    ]);

    $this->assertDatabaseHas('schedule_assignments', [
        'schedule_id' => $schedule->id,
        'user_id' => $user->id,
        'shift_id' => $shift->id,
    ]);
});

test('Employee tidak dapat assign employee ke schedule', function () {
    //Arrange
    \App\Models\User::factory()->create()->assignRole('Employee');
    $schedule = \App\Models\Schedule::factory()->create();
    $user = \App\Models\User::factory()->create();
    $shift = \App\Models\Shift::factory()->create();

    $data = [
        'user_id' => $user->id,
        'shift_id' => $shift->id,
    ];

    //Act
    $response = $this->actingAs(\App\Models\User::first())->postJson("/api/v1/schedules/{$schedule->id}/assign", $data);

    //Assert
    $response->assertStatus(403);
});

test('Tidak dapat assign user yang sama ke schedule yang sama', function () {
    //Arrange
    \App\Models\User::factory()->create()->assignRole('Hr');
    $schedule = \App\Models\Schedule::factory()->create();
    $user = \App\Models\User::factory()->create();
    $shift = \App\Models\Shift::factory()->create();

    // Assign pertama
    \App\Models\ScheduleAssignment::create([
        'schedule_id' => $schedule->id,
        'user_id' => $user->id,
        'shift_id' => $shift->id,
    ]);

    // Coba assign lagi
    $data = [
        'user_id' => $user->id,
        'shift_id' => $shift->id,
    ];

    //Act
    $response = $this->actingAs(\App\Models\User::first())->postJson("/api/v1/schedules/{$schedule->id}/assign", $data);

    //Assert
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['user_id']);
});

test('Employee dapat melihat jadwal pribadi', function () {
    //Arrange
    $user = \App\Models\User::factory()->create()->assignRole('Employee');
    $schedule = \App\Models\Schedule::factory()->create();
    $shift = \App\Models\Shift::factory()->create();

    \App\Models\ScheduleAssignment::create([
        'schedule_id' => $schedule->id,
        'user_id' => $user->id,
        'shift_id' => $shift->id,
    ]);

    //Act
    $response = $this->actingAs($user)->getJson('/api/v1/my-schedule');

    //Assert
    $response->assertStatus(200);
    $response->assertJsonCount(1, 'data');
});

test('Hr dapat melihat assignments per schedule', function () {
    //Arrange
    \App\Models\User::factory()->create()->assignRole('Hr');
    $schedule = \App\Models\Schedule::factory()->create();
    $user1 = \App\Models\User::factory()->create();
    $user2 = \App\Models\User::factory()->create();
    $shift = \App\Models\Shift::factory()->create();

    \App\Models\ScheduleAssignment::create([
        'schedule_id' => $schedule->id,
        'user_id' => $user1->id,
        'shift_id' => $shift->id,
    ]);
    \App\Models\ScheduleAssignment::create([
        'schedule_id' => $schedule->id,
        'user_id' => $user2->id,
        'shift_id' => $shift->id,
    ]);

    //Act
    $response = $this->actingAs(\App\Models\User::first())->getJson("/api/v1/schedules/{$schedule->id}/assignments");

    //Assert
    $response->assertStatus(200);
    $response->assertJsonCount(2, 'data');
});

test('Employee tidak dapat melihat assignments per schedule', function () {
    //Arrange
    \App\Models\User::factory()->create()->assignRole('Employee');
    $schedule = \App\Models\Schedule::factory()->create();

    //Act
    $response = $this->actingAs(\App\Models\User::first())->getJson("/api/v1/schedules/{$schedule->id}/assignments");

    //Assert
    $response->assertStatus(403);
});
