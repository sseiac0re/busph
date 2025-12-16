<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ensure a default admin account exists
        User::updateOrCreate(
            ['email' => 'busph.help@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('BusPH@admin2025=!'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
