<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Sumsang Gala S26 Pro',
            'description' => 'A high-end smartphone with a large display and powerful processor.',
            'price' => 5000000,
            'quantity' => 10,
        ])->category()->attach(1); // Assign to Electronics category

        Product::create([
            'name' => 'Baju Batik Modern',
            'description' => 'A stylish modern batik shirt suitable for formal and casual occasions.',
            'price' => 250000,
            'quantity' => 30,
        ])->category()->attach(2); // Assign to Fashion category

        Product::create([
            'name' => 'Buku Pemrograman Laravel',
            'description' => 'A comprehensive guide to learning Laravel framework for web development.',
            'price' => 200000,
            'quantity' => 15,
        ])->category()->attach(3); // Assign to Books category

        Product::create([
            'name' => 'Kulkas Sanyo 2 Pintu',
            'description' => 'A reliable refrigerator with a large capacity and efficient cooling system.',
            'price' => 3500000,
            'quantity' => 5,
        ])->category()->attach(1); // Assign to Electronics category

        Product::create([
            'name' => 'Sepatu Nike Air Max',
            'description' => 'Comfortable and stylish sneakers for everyday wear.',
            'price' => 1500000,
            'quantity' => 20,
        ])->category()->attach(2); // Assign to Fashion category

        Product::create([
            'name' => 'Meja Belajar Kayu',
            'description' => 'A sturdy wooden desk for studying or working from home.',
            'price' => 800000,
            'quantity' => 8,
        ])->category()->attach(4); // Assign to Furniture category

    }


}
