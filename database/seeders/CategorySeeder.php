<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Category::create([
            'name' => 'Electronics',
            'description' => 'Electronic devices and gadgets',
        ]);

        Category::create([
            'name' => 'Clothing',
            'description' => 'Apparel and fashion items',
        ]);

        Category::create([
            'name' => 'Books',
            'description' => 'Books and literature',
        ]);

        Category::create([
            'name' => 'Home & Kitchen',
            'description' => 'Household items and kitchenware',
        ]);

        Category::create([
            'name' => 'Sports & Outdoors',
            'description' => 'Sports equipment and outdoor gear',
        ]);

        Category::create([
            'name' => 'Beauty & Personal Care',
            'description' => 'Beauty products and personal care items',
        ]);
    }
}
