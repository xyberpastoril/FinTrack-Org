<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create User Accounts for Admin and User
        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'is_admin' => true,
        ]);
    }
}
