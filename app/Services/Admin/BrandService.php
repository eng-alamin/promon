<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Brand;
use DomainException;

class BrandService
{
    /**
     * Create a brand, or update it when one is given.
     *
     * @param  array{name: string, slug: string, is_active: bool}  $data
     */
    public function save(?Brand $brand, array $data): Brand
    {
        $payload = [
            'name' => trim($data['name']),
            'slug' => $data['slug'],
            'is_active' => (bool) $data['is_active'],
        ];

        if ($brand === null) {
            return Brand::create($payload);
        }

        $brand->update($payload);

        return $brand;
    }

    public function toggleActive(Brand $brand): void
    {
        $brand->update(['is_active' => ! $brand->is_active]);
    }

    /**
     * @throws DomainException when products (even soft deleted ones) still use this brand
     */
    public function delete(Brand $brand): void
    {
        if ($brand->products()->withTrashed()->exists()) {
            throw new DomainException(
                "Cannot delete \"{$brand->name}\" because products use it. Make it inactive instead."
            );
        }

        $brand->delete();
    }
}
