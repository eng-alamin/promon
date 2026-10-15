<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPriceTier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Demo catalogue taken from the static site: 10 categories and 25 sample products.
 * Names, prices, MOQ and tiers are SAMPLE values, replace them with the real catalogue in the admin panel.
 * Safe to run again: anything that already exists (matched by name or slug) is left untouched,
 * so edits made in the admin panel are never overwritten.
 */
class DemoCatalogueSeeder extends Seeder
{
    /** @var array<int, string> */
    private const CATEGORIES = [
        'Supplements',
        'Medicine',
        'Baby Food & Milk',
        'Grocery',
        'Nappies',
        'Chocolate',
        'Gift Items',
        'Health Products',
        'Beauty Products',
        'Shoes & Bags',
    ];

    /**
     * name, category, price, moq, pack size, status, popular, new, tiers [[min qty, price]], Unsplash photo id.
     * Brand, origin and shelf life are left out on purpose: fill them only with real information.
     *
     * @var array<int, array<int, mixed>>
     */
    public const PRODUCTS = [
        ['Vitamin D3 tablets, 180 count', 'Supplements', '8.99', 12, 'Carton of 12', 'in_stock', true, false, [[12, '8.99'], [50, '8.49'], [100, '7.99']], '1644432757359-b184377d9eb0'],
        ['Daily multivitamin, 90 tablets', 'Supplements', '12.50', 12, 'Carton of 12', 'in_stock', true, false, [], '1763668177859-0ed5669a795e'],
        ['Gentle foaming face cleanser, 150ml', 'Beauty Products', '7.25', 12, 'Carton of 12', 'in_stock', true, false, [[12, '7.25'], [50, '6.85']], '1748639320154-6ba118bccc74'],
        ['Brightening vitamin C serum, 30ml', 'Beauty Products', '14.99', 12, 'Carton of 12', 'in_stock', true, false, [], '1713768704571-6aeb0d0e5105'],
        ['Repair shampoo, 400ml', 'Beauty Products', '4.50', 12, 'Carton of 12', 'advance_order', true, false, [], '1629198726018-604230bdb091'],
        ['Shea body lotion, 400ml', 'Beauty Products', '6.80', 12, 'Carton of 12', 'in_stock', false, false, [], '1624377225030-f0bb343eaa4d'],
        ['Matte lipstick', 'Beauty Products', '5.50', 12, 'Carton of 12', 'in_stock', true, false, [], '1625093742435-6fa192b6fb10'],
        ['Soothing baby cream, 100ml', 'Health Products', '3.99', 12, 'Carton of 12', 'in_stock', true, false, [], '1585945037805-5fd82c2e60b1'],
        ['Men\'s cologne, 50ml', 'Beauty Products', '12.99', 12, 'Carton of 12', 'advance_order', false, false, [], '1665763630810-e6251bdd392d'],
        ['Instant coffee, 200g', 'Grocery', '5.99', 12, 'Carton of 12', 'in_stock', true, false, [], '1663407746753-bf168bc724f4'],
        ['Argan hair oil, 100ml', 'Beauty Products', '9.75', 12, 'Carton of 12', 'in_stock', false, true, [], '1710410815589-dd83514104d0'],
        ['Hydrating facial toner, 150ml', 'Beauty Products', '8.40', 12, 'Carton of 12', 'in_stock', false, false, [], '1620916297397-a4a5402a3c6c'],
        ['Vitamin E body oil, 30ml', 'Beauty Products', '7.95', 12, 'Carton of 12', 'advance_order', false, false, [], '1696025522422-aa9a74e4f3d5'],
        ['Orange hand cream, 75ml', 'Beauty Products', '3.85', 12, 'Carton of 12', 'in_stock', false, true, [], '1615396899839-c99c121888b0'],
        ['Aloe soothing gel, 100ml', 'Health Products', '4.25', 12, 'Carton of 12', 'in_stock', false, false, [], '1609175214983-594001465d18'],
        ['Daily face moisturiser, 50ml', 'Beauty Products', '10.99', 12, 'Carton of 12', 'in_stock', false, true, [], '1627811015433-368c148f6c3c'],
        ['Nourishing body wash, 500ml', 'Health Products', '3.50', 12, 'Carton of 12', 'advance_order', false, false, [], '1624377578244-098e7bf2d55a'],
        ['Travel-size care set, 5 items', 'Health Products', '11.50', 12, 'Carton of 12', 'in_stock', false, false, [], '1649073005971-37babef31983'],
        ['Clarifying gel cleanser, 200ml', 'Beauty Products', '6.95', 12, 'Carton of 12', 'in_stock', false, false, [], '1745141063798-7fa04698ea80'],
        ['Omega-3 fish oil capsules, 120 count', 'Supplements', '9.49', 12, 'Carton of 12', 'in_stock', false, true, [], '1518842013791-b874be246c34'],
        ['Milk chocolate bar, 100g', 'Chocolate', '1.49', 24, 'Carton of 24', 'in_stock', false, true, [], '1623660053975-cf75a8be0908'],
        ['Assorted chocolate bars multipack', 'Chocolate', '9.99', 12, 'Carton of 12', 'in_stock', false, true, [], '1621939514649-280e2ee25f60'],
        ['Hazelnut chocolate bar, 100g', 'Chocolate', '1.79', 24, 'Carton of 24', 'in_stock', false, true, [], '1638194645412-1d0b4c53ffed'],
        ['Dark chocolate bar, 85% cocoa, 100g', 'Chocolate', '1.99', 24, 'Carton of 24', 'advance_order', false, false, [], '1626697556651-67ebdcb8cbd6'],
        ['Infant formula milk powder, 400g', 'Baby Food & Milk', '8.50', 12, 'Carton of 12', 'in_stock', false, true, [], '1623707430616-d9f956bcac2b'],
    ];

    public function run(): void
    {
        $categories = [];

        foreach (self::CATEGORIES as $index => $name) {
            $categories[$name] = Category::firstOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name), 'sort_order' => $index + 1, 'is_active' => true],
            );
        }

        foreach (self::PRODUCTS as $index => [$name, $category, $price, $moq, $pack, $status, $popular, $new, $tiers]) {
            $slug = Str::slug($name);

            $product = Product::withTrashed()->where('slug', $slug)->first();
            if ($product !== null) {
                continue;
            }

            $product = Product::create([
                'category_id' => $categories[$category]->id,
                'name' => $name,
                'slug' => $slug,
                'price' => $price,
                'moq' => $moq,
                'pack_size' => $pack,
                'status' => $status,
                'is_popular' => $popular,
                'is_new' => $new,
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);

            foreach ($tiers as [$minQty, $tierPrice]) {
                ProductPriceTier::create(['product_id' => $product->id, 'min_qty' => $minQty, 'price' => $tierPrice]);
            }
        }
    }
}
