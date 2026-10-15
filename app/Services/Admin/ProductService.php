<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Product;

class ProductService
{
    /**
     * Create a product, or update it when one is given.
     *
     * @param  array<string, mixed>  $data  Validated data from the product form
     */
    public function save(?Product $product, array $data): Product
    {
        $payload = [
            'category_id' => (int) $data['category_id'],
            'brand_id' => filled($data['brand_id'] ?? null) ? (int) $data['brand_id'] : null,
            'name' => trim((string) $data['name']),
            'slug' => $data['slug'],
            'sku' => $data['sku'] ?? null,
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'moq' => filled($data['moq'] ?? null) ? (int) $data['moq'] : null,
            'pack_size' => $data['pack_size'] ?? null,
            'status' => $data['status'],
            'origin_country' => $data['origin_country'] ?? null,
            'shelf_life' => $data['shelf_life'] ?? null,
            'bd_approval' => $data['bd_approval'] ?? null,
            'is_popular' => (bool) $data['is_popular'],
            'is_new' => (bool) $data['is_new'],
            'is_active' => (bool) $data['is_active'],
            'sort_order' => (int) $data['sort_order'],
        ];

        if ($product === null) {
            return Product::create($payload);
        }

        $product->update($payload);

        return $product;
    }

    public function toggleActive(Product $product): void
    {
        $product->update(['is_active' => ! $product->is_active]);
    }

    /**
     * Soft delete: old enquiries keep pointing to the product, and its slug stays reserved.
     */
    public function delete(Product $product): void
    {
        $product->delete();
    }
}
