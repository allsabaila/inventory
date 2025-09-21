<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tambah admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        // Tambah beberapa staff
        User::insert([
            [
                'name' => 'User 1',
                'email' => 'user1@example.com',
                'password' => bcrypt('password1'),
                'role' => 'staff',
            ],
            [
                'name' => 'User 2',
                'email' => 'user2@example.com',
                'password' => bcrypt('password2'),
                'role' => 'staff',
            ],
        ]);
    }
}
