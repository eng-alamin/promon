<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Product;
use App\Models\ProductPriceTier;

class ProductPriceTierService
{
    public const MAX_TIERS = 10;

    public function count(Product $product): int
    {
        return ProductPriceTier::where('product_id', $product->id)->count();
    }

    /**
     * @param  array{min_qty: int|string, price: string}  $data
     */
    public function save(Product $product, ?ProductPriceTier $tier, array $data): ProductPriceTier
    {
        $payload = [
            'min_qty' => (int) $data['min_qty'],
            'price' => $data['price'],
        ];

        if ($tier === null) {
            return ProductPriceTier::create($payload + ['product_id' => $product->id]);
        }

        $tier->update($payload);

        return $tier;
    }

    public function delete(ProductPriceTier $tier): void
    {
        $tier->delete();
    }
}
