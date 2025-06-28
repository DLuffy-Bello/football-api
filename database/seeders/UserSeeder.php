<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@football.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Viewer user
        $viewer = User::create([
            'name' => 'Viewer User',
            'email' => 'viewer@football.com',
            'password' => Hash::make('viewer123'),
            'email_verified_at' => now(),
        ]);
        $viewer->assignRole('viewer');

        // Test user
        $test = User::create([
            'name' => 'Test User',
            'email' => 'test@football.com',
            'password' => Hash::make('test123'),
            'email_verified_at' => now(),
        ]);
    }
}
