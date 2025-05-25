<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed de database van de applicatie.
     */
    public function run(): void
    {
        // Voorbeeld: maak 10 dummy gebruikers aan
        // \App\Models\User::factory(10)->create();

        // Voorbeeld: maak een specifieke testgebruiker aan
        // \App\Models\User::factory()->create([
        //     'name' => 'Test Gebruiker',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            AdminUserSeeder::class,
            // Andere seeders kunnen hier toegevoegd worden
        ]);
    }
}
