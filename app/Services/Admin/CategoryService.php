<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Category;
use DomainException;

class CategoryService
{
    /**
     * Create a category, or update it when one is given.
     *
     * @param  array{name: string, slug: string, sort_order: int|string, is_active: bool}  $data
     */
    public function save(?Category $category, array $data): Category
    {
        $payload = [
            'name' => trim($data['name']),
            'slug' => $data['slug'],
            'sort_order' => (int) $data['sort_order'],
            'is_active' => (bool) $data['is_active'],
        ];

        if ($category === null) {
            return Category::create($payload);
        }

        $category->update($payload);

        return $category;
    }

    public function toggleActive(Category $category): void
    {
        $category->update(['is_active' => ! $category->is_active]);
    }

    /**
     * @throws DomainException when enquiries still use this category
     */
    public function delete(Category $category): void
    {
        if ($category->enquiries()->exists()) {
            throw new DomainException(
                "Cannot delete \"{$category->name}\" because enquiries use it. Make it inactive instead."
            );
        }

        $category->delete();
    }
}