<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'supplements', 'name' => 'Supplements'],
            ['slug' => 'medicine', 'name' => 'Medicine'],
            ['slug' => 'baby-food-milk', 'name' => 'Baby Food & Milk'],
            ['slug' => 'grocery', 'name' => 'Grocery'],
            ['slug' => 'nappies', 'name' => 'Nappies'],
            ['slug' => 'chocolate', 'name' => 'Chocolate'],
            ['slug' => 'gifts', 'name' => 'Gift Items'],
            ['slug' => 'health-products', 'name' => 'Health Products'],
            ['slug' => 'beauty', 'name' => 'Beauty Products'],
            ['slug' => 'shoes-bags', 'name' => 'Shoes & Bags'],
        ];

        foreach ($categories as $index => $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
