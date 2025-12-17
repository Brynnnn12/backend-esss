<?php

use App\Models\Shift;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'Hr']);
    Role::create(['name' => 'Employee']);
});

test('mendapatkan semua shift', function () {
    //Arrange
    User::factory()->create()->assignRole('Hr');

    Shift::factory()->count(3)->create();

    //Act
    $response = $this->actingAs(User::first())->getJson('/api/v1/shifts');

    //Assert
    $response->assertStatus(200);
    $response->assertJsonCount(3, 'data');
});

test('Employee tidak dapat mendapatkan semua shift', function () {
    //Arrange
    User::factory()->create()->assignRole('Employee');

    Shift::factory()->count(3)->create();

    //Act
    $response = $this->actingAs(User::first())->getJson('/api/v1/shifts');

    //Assert
    $response->assertStatus(403);
});
