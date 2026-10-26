<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // System permissions

        //User management permissions
        \App\Models\Permission::create([
            'code' => 'SYSTEM:MANAGE_USERS',
            'description' => 'Permission to manage users',
        ]);
        \App\Models\Permission::create([
            'code' => 'SYSTEM:VIEW_USERS',
            'description' => 'Permission to view users',
        ]);

        // Product management permissions
        \App\Models\Permission::create([
            'code' => 'SYSTEM:MANAGE_PRODUCTS',
            'description' => 'Permission to manage products',
        ]);

        \App\Models\Permission::create([
            'code' => 'SYSTEM:VIEW_PRODUCTS',
            'description' => 'Permission to view products',
        ]);

        // Category management permissions
        \App\Models\Permission::create([
            'code' => 'SYSTEM:MANAGE_CATEGORIES',
            'description' => 'Permission to manage categories',
        ]);

        \App\Models\Permission::create([
            'code' => 'SYSTEM:VIEW_CATEGORIES',
            'description' => 'Permission to view categories',
        ]);


        // Role management permissions
        \App\Models\Permission::create([
            'code' => 'SYSTEM:MANAGE_ROLES',
            'description' => 'Permission to manage roles',
        ]);

        \App\Models\Permission::create([
            'code' => 'SYSTEM:VIEW_ROLES',
            'description' => 'Permission to view roles',
        ]);

        // Permission management permissions
        \App\Models\Permission::create([
            'code' => 'SYSTEM:MANAGE_PERMISSIONS',
            'description' => 'Permission to manage permissions',
        ]);

        \App\Models\Permission::create([
            'code' => 'SYSTEM:VIEW_PERMISSIONS',
            'description' => 'Permission to view permissions',
        ]);

        // Order management permissions
        \App\Models\Permission::create([
            'code' => 'SYSTEM:MANAGE_ORDERS',
            'description' => 'Permission to manage orders',
        ]);

        \App\Models\Permission::create([
            'code' => 'SYSTEM:PLACE_ORDERS',
            'description' => 'Permission to place orders',
        ]);

        // Cart management permissions
        \App\Models\Permission::create([
            'code' => 'SYSTEM:MANAGE_CART',
            'description' => 'Permission to manage cart',
        ]);
        \App\Models\Permission::create([
            'code' => 'SYSTEM:VIEW_CART',
            'description' => 'Permission to view cart',
        ]);


        // Subscriber permissions
        \App\Models\Permission::create([
            'code' => 'SUBSCRIBER:CARD_SILVER',
            'description' => 'Permission to get a discount on the silver card',
        ]);

        \App\Models\Permission::create([
            'code' => 'SUBSCRIBER:CARD_GOLD',
            'description' => 'Permission to get a discount on the gold card',
        ]);

        \App\Models\Permission::create([
            'code' => 'SUBSCRIBER:CARD_PLATINUM',
            'description' => 'Permission to get a discount on the platinum card',
        ]);
    }
}
