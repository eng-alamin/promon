<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * 61 categories. The first 10 keep their old slugs, so existing enquiries stay linked.
     * Safe to run again: categories are matched by slug and updated, never duplicated.
     * To hide one from the public site, switch it off in Admin > Categories.
     */
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
            ['slug' => 'vitamins', 'name' => 'Vitamins & Minerals'],
            ['slug' => 'fish-oil-omega', 'name' => 'Fish Oil & Omega'],
            ['slug' => 'sports-nutrition', 'name' => 'Sports Nutrition & Protein'],
            ['slug' => 'herbal-remedies', 'name' => 'Herbal Remedies'],
            ['slug' => 'cold-flu', 'name' => 'Cold & Flu'],
            ['slug' => 'pain-relief', 'name' => 'Pain Relief'],
            ['slug' => 'first-aid', 'name' => 'First Aid'],
            ['slug' => 'digestive-health', 'name' => 'Digestive Health'],
            ['slug' => 'eye-ear-care', 'name' => 'Eye & Ear Care'],
            ['slug' => 'oral-care', 'name' => 'Oral Care'],
            ['slug' => 'baby-formula', 'name' => 'Baby Formula'],
            ['slug' => 'baby-toiletries', 'name' => 'Baby Toiletries'],
            ['slug' => 'baby-feeding', 'name' => 'Feeding & Weaning'],
            ['slug' => 'baby-clothes', 'name' => 'Baby Clothes'],
            ['slug' => 'toys', 'name' => 'Toys'],
            ['slug' => 'skincare', 'name' => 'Skincare'],
            ['slug' => 'makeup', 'name' => 'Makeup & Cosmetics'],
            ['slug' => 'hair-care', 'name' => 'Hair Care'],
            ['slug' => 'body-care', 'name' => 'Body Care'],
            ['slug' => 'sun-holiday', 'name' => 'Sun & Holiday'],
            ['slug' => 'fragrance', 'name' => 'Perfumes & Fragrance'],
            ['slug' => 'mens-grooming', 'name' => "Men's Grooming"],
            ['slug' => 'womens-health', 'name' => "Women's Health"],
            ['slug' => 'feminine-hygiene', 'name' => 'Feminine Hygiene'],
            ['slug' => 'toiletries', 'name' => 'Toiletries'],
            ['slug' => 'personal-care', 'name' => 'Personal Care & Hygiene'],
            ['slug' => 'biscuits-snacks', 'name' => 'Biscuits & Snacks'],
            ['slug' => 'tea-coffee', 'name' => 'Tea & Coffee'],
            ['slug' => 'sweets', 'name' => 'Sweets & Confectionery'],
            ['slug' => 'breakfast-cereals', 'name' => 'Breakfast & Cereals'],
            ['slug' => 'sauces-spices', 'name' => 'Sauces & Spices'],
            ['slug' => 'drinks', 'name' => 'Drinks & Squash'],
            ['slug' => 'clothing', 'name' => 'Clothing'],
            ['slug' => 'mens-clothing', 'name' => "Men's Clothing"],
            ['slug' => 'womens-clothing', 'name' => "Women's Clothing"],
            ['slug' => 'kids-clothing', 'name' => "Kids' Clothing"],
            ['slug' => 'shoes', 'name' => 'Shoes'],
            ['slug' => 'bags-luggage', 'name' => 'Bags & Luggage'],
            ['slug' => 'watches-jewellery', 'name' => 'Watches & Jewellery'],
            ['slug' => 'fashion-accessories', 'name' => 'Fashion Accessories'],
            ['slug' => 'electricals', 'name' => 'Electricals'],
            ['slug' => 'phones-accessories', 'name' => 'Phones & Accessories'],
            ['slug' => 'small-appliances', 'name' => 'Small Appliances'],
            ['slug' => 'shavers-hair-tools', 'name' => 'Shavers & Hair Tools'],
            ['slug' => 'home-household', 'name' => 'Home & Household'],
            ['slug' => 'kitchenware', 'name' => 'Kitchenware'],
            ['slug' => 'cleaning', 'name' => 'Cleaning Products'],
            ['slug' => 'laundry', 'name' => 'Laundry & Detergents'],
            ['slug' => 'stationery', 'name' => 'Stationery'],
            ['slug' => 'pet-care', 'name' => 'Pet Care'],
            ['slug' => 'other-uk-products', 'name' => 'Other UK Products'],
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