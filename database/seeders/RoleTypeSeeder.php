<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\RoleType::create([
            'name' => 'System',
            'description' => 'System role type for system-level access',
        ]);

        \App\Models\RoleType::create([
            'name' => 'Subscriber',
            'description' => 'Subscriber role type for benefit access',
        ]);
    }
}
