<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Galih',
            'email' => 'galih@gmail.com',
            'password' => bcrypt('qwerty1234'),
        ])->role()->attach([
            1 => [
                'start_date' => now(),
                'end_date' => null,
            ],
            6 =>[
                'start_date' => now(),
                'end_date' => now()->addYear(1),
                ],
        ]); // Assign Admin and Platinum roles to the first user


        \App\Models\User::factory(10)->create([
            'name' => fn() => fake()->name(),
            'email' => fn() => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
        ])->each(function ($user) {
            $user->role()->attach([
                2 => [
                    'start_date' => now(),
                    'end_date' => null,
                ],
                4 => [
                    'start_date' => now(),
                    'end_date' => now()->addYear(1),
                ],
            ]); // Assign User and Silver roles to the other users
        });
    }
}
