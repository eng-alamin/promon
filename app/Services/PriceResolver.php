<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\ProductPriceTier;

/**
 * Works out the price per unit for a quantity. Used for the enquiry price snapshot,
 * and later by the public site.
 */
class PriceResolver
{
    /**
     * The price of the biggest quantity tier the order reaches, or the base price when none applies.
     * Returned with 2 decimals, for example "9.50".
     */
    public function unitPrice(Product $product, int $quantity): string
    {
        $tier = ProductPriceTier::where('product_id', $product->id)
            ->where('min_qty', '<=', $quantity)
            ->orderByDesc('min_qty')
            ->first();

        return number_format((float) ($tier?->price ?? $product->price), 2, '.', '');
    }
}
