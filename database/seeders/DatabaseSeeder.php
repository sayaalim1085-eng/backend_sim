<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role Spatie
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Buat user
        $user = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Assign role Spatie ke user
        $user->assignRole($adminRole);
    }
}