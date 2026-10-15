<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * Downloads one sample photo per demo product and saves it on the public disk, the same place
 * the admin upload uses (storage/app/public/products/{id}/), so the site serves it from your own server.
 *
 * Needs internet on the machine that runs it and `php artisan storage:link`.
 * Photos are free-to-use Unsplash photos (Unsplash License, https://unsplash.com/license).
 * They are generic stock photos, NOT photos of these exact products. Replace them in the admin panel.
 *
 * Products that already have an image are skipped. A failed download is reported and skipped;
 * the product then shows the letter tile. Run again later to retry only the missing ones:
 *     php artisan db:seed --class=DemoImageSeeder
 */
class DemoImageSeeder extends Seeder
{
    public function run(): void
    {
        $saved = 0;
        $failed = 0;

        foreach (DemoCatalogueSeeder::PRODUCTS as $row) {
            $product = Product::where('slug', Str::slug($row[0]))->first();

            if ($product === null || ProductImage::where('product_id', $product->id)->exists()) {
                continue;
            }

            $url = "https://images.unsplash.com/photo-{$row[9]}?auto=format&fit=crop&w=800&h=600&q=70";

            try {
                $response = Http::timeout(20)->retry(2, 500)->get($url);

                if (! $response->successful() || ! str_starts_with((string) $response->header('Content-Type'), 'image/')) {
                    throw new \RuntimeException('HTTP ' . $response->status());
                }

                $path = "products/{$product->id}/" . Str::random(20) . '.jpg';
                Storage::disk('public')->put($path, $response->body());

                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'alt_text' => $product->name,
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);
                $saved++;
            } catch (Throwable $e) {
                $failed++;
                $this->command?->warn("No image for \"{$product->name}\": {$e->getMessage()}");
            }
        }

        $this->command?->info("Product images saved: {$saved}, failed: {$failed}.");
    }
}
