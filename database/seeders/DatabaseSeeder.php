<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@sparklepayroll.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'normal_hour_rate' => 0,
            'ot_hour_rate' => 0,
        ]);

        // Create test employee user
        User::factory()->create([
            'name' => 'Test Employee',
            'email' => 'employee@example.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'normal_hour_rate' => 25.00,
            'ot_hour_rate' => 37.50,
        ]);
    }
}
