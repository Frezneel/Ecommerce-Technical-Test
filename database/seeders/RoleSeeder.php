<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // API Roles
        \App\Models\Role::create([
            'name' => 'admin',
            'description' => 'Administrator role with full access',
            'type_role_id' => 1, // System Role Type
        ]);

        \App\Models\Role::create([
            'name' => 'user',
            'description' => 'Regular user role with limited access',
            'type_role_id' => 1,
        ]);

        \App\Models\Role::create([
            'name' => 'guest',
            'description' => 'Guest role with minimal access',
            'type_role_id' => 1, // System Role Type

        ]);

        // Subscriber roles
        \App\Models\Role::create([
            'name' => 'silver_support',
            'description' => 'silver support card discount, include free shipping weekly',
            'type_role_id' => 2, // Subscriber
        ]);

        \App\Models\Role::create([
            'name' => 'gold_support',
            'description' => 'gold support card discount, include free shipping weekly, includes silver support',
            'type_role_id' => 2, // Subscriber
        ]);

        \app\Models\Role::create([
            'name' => 'platinum_support',
            'description' => 'platinum support card discount daily, include free shipping, includes gold support',
            'type_role_id' => 2, // Subscriber
        ]);
    }
}
