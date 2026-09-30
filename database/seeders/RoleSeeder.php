<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Permission dasar
        $permissionMaster = Permission::create(['name' => 'manage master data']);

        // 2. Buat Role
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleUser  = Role::create(['name' => 'user']);

        // 3. Berikan permission ke role admin
        $roleAdmin->givePermissionTo($permissionMaster);

        // 4. Buat Akun Admin Default untuk Pengujian
        $admin = User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password123'),
        ]);
        $admin->assignRole($roleAdmin);

        // 5. Buat Akun User Biasa untuk Pengujian
        $user = User::create([
            'name'     => 'User Biasa',
            'email'    => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole($roleUser);
    }
}