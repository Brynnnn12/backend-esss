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
