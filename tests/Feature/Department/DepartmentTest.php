<?php

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'Hr']);
    Role::create(['name' => 'Employee']);
});

test('Mendapatkan Semua Departemen', function () {
    //Arrange
    User::factory()->create()->assignRole('Hr');

    Department::factory()->count(3)->create();

    //Act
    $response = $this->actingAs(User::first())->getJson('/api/v1/departments');

    //Assert
    $response->assertStatus(200);
    $response->assertJsonCount(3, 'data');
});

test('Employee Tidak Dapat Mendapatkan Semua Departemen', function () {
    //Arrange
    User::factory()->create()->assignRole('Employee');

    Department::factory()->count(3)->create();

    //Act
    $response = $this->actingAs(User::first())->getJson('/api/v1/departments');

    //Assert
    $response->assertStatus(403);
});

test('Guest Tidak Dapat Mendapatkan Semua Departemen', function () {
    //Arrange
    Department::factory()->count(3)->create();

    //Act
    $response = $this->getJson('/api/v1/departments');

    //Assert
    $response->assertStatus(500);
});

test('Membuat Departemen Baru', function () {
    //Arrange
    User::factory()->create()->assignRole('Hr');

    $departmentData = [
        'name' => 'IT Department',
        'description' => 'Handles all IT related tasks',
    ];

    //Act
    $response = $this->actingAs(User::first())->postJson('/api/v1/departments', $departmentData);

    //Assert
    $response->assertStatus(201);
    $this->assertDatabaseHas('departments', ['name' => 'IT Department']);
});
