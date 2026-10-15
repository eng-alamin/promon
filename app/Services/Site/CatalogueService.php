<?php

declare(strict_types=1);

namespace App\Services\Site;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

/**
 * Read-only queries for the public site. The public site shows categories only
 * (no products and no prices), so only active categories are returned.
 */
class CatalogueService
{
    /** @return Collection<int, Category> */
    public function categories(): Collection
    {
        return Category::active()->ordered()->get();
    }
}
