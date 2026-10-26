<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assign permissions to roles
        // Role 1: Admin - has all permissions
        \App\Models\Role::find(1)->permission()->attach([
            1, // SYSTEM:MANAGE_USERS
            2, // SYSTEM:VIEW_USERS
            3, // SYSTEM:MANAGE_PRODUCTS
            4, // SYSTEM:VIEW_PRODUCTS
            5, // SYSTEM:MANAGE_CATEGORIES
            6, // SYSTEM:VIEW_CATEGORIES
            7, // SYSTEM:MANAGE_ROLES
            8, // SYSTEM:VIEW_ROLES
            9, // SYSTEM:MANAGE_PERMISSIONS
            10, // SYSTEM:VIEW_PERMISSIONS
            11, // SYSTEM:MANAGE_ORDERS
            12, // SYSTEM:PLACE_ORDERS
            13, // SUBSCRIPTION:VIEW_SILVER_CONTENT
            14, // SUBSCRIPTION:VIEW_GOLD_CONTENT
            15, // SUBSCRIPTION:VIEW_PLATINUM_CONTENT
            16, // SYSTEM:MANAGE_CART
            17, // SYSTEM:VIEW_CART
        ]);

        // Role 2: User - has limited permissions
        \App\Models\Role::find(2)->permission()->attach([
            2, // SYSTEM:VIEW_USERS
            4, // SYSTEM:VIEW_PRODUCTS
            6, // SYSTEM:VIEW_CATEGORIES
            12, // SYSTEM:PLACE_ORDERS
            16, // SYSTEM:MANAGE_CART
            17, // SYSTEM:VIEW_CART
        ]);

        // Role 4: Silver - has limited permissions
        \App\Models\Role::find(4)->permission()->attach([
            13, // SUBSCRIPTION:VIEW_SILVER_CONTENT
        ]);

        // Role 5: Gold - has limited permissions
        \App\Models\Role::find(5)->permission()->attach([
            14, // SUBSCRIPTION:VIEW_GOLD_CONTENT
        ]);

        // Role 6: Platinum - has limited permissions
        \App\Models\Role::find(6)->permission()->attach([
            15, // SUBSCRIPTION:VIEW_PLATINUM_CONTENT
        ]);
    }
}
