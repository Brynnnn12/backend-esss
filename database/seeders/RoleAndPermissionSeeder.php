<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        //membuat role admin
        $adminRole = Role::create([
            'name' => 'Hr',
        ]);

        //membuat role user
        $userRole = Role::create([
            'name' => 'Employee',
        ]);

        //membuat akun Hr
        $admin = User::create([
            'name' => 'hr',
            'email' => 'hr@gmail.com',
            'password' => bcrypt('hr12345'),
        ]);

        //memberi role admin ke akun admin
        $admin->assignRole($adminRole);

        $employee = User::create([
            'name' => 'employee',
            'email' => 'employee@gmail.com',
            'password' => bcrypt('employee12345'),
        ]);

        //memberi role user ke akun user
        $employee->assignRole($userRole);
    }
}
