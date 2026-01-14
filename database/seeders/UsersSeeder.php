<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Administrator
        User::create([
            'name' => 'Marko Petrović',
            'username' => 'marko.petrovic',
            'email' => 'marko.petrovic@raf.rs',
            'password' => Hash::make('password'),
            'role' => 'administrator',
            'email_verified_at' => now(),
        ]);

        // Inženjeri
        User::create([
            'name' => 'Ana Jovanović',
            'username' => 'ana.jovanovic',
            'email' => 'ana.jovanovic@raf.rs',
            'password' => Hash::make('password'),
            'role' => 'engineer',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Stefan Nikolić',
            'username' => 'stefan.nikolic',
            'email' => 'stefan.nikolic@raf.rs',
            'password' => Hash::make('password'),
            'role' => 'engineer',
            'email_verified_at' => now(),
        ]);

        // Klijenti
        User::create([
            'name' => 'Milan Stojanović',
            'username' => 'milan.stojanovic',
            'email' => 'milan.stojanovic@raf.rs',
            'password' => Hash::make('password'),
            'role' => 'client',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Jovana Popović',
            'username' => 'jovana.popovic',
            'email' => 'jovana.popovic@raf.rs',
            'password' => Hash::make('password'),
            'role' => 'client',
            'email_verified_at' => now(),
        ]);
    }
}
