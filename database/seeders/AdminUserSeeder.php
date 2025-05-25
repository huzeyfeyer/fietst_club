<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Importeer User model

class AdminUserSeeder extends Seeder
{
    /**
     * Voer de database seeds uit.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@ehb.be',
            'email_verified_at' => now(),
            'password' => Hash::make('Password!321'),
            'role' => User::ROLE_ADMIN, // Gebruik de constante van het User model
        ]);
    }
}
