<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ProductImageService
{
    public const MAX_IMAGES = 10;

    private const DISK = 'public';

    public function count(Product $product): int
    {
        return ProductImage::where('product_id', $product->id)->count();
    }

    public function store(Product $product, UploadedFile $file): ProductImage
    {
        $hasImages = ProductImage::where('product_id', $product->id)->exists();
        $next = ((int) (ProductImage::where('product_id', $product->id)->max('sort_order') ?? -1)) + 1;

        $path = $file->store("products/{$product->id}", self::DISK);

        if ($path === false) {
            throw new RuntimeException('The image could not be saved.');
        }

        return ProductImage::create([
            'product_id' => $product->id,
            'path' => $path,
            'alt_text' => $product->name,
            'is_primary' => ! $hasImages,
            'sort_order' => $next,
        ]);
    }

    public function setPrimary(ProductImage $image): void
    {
        DB::transaction(function () use ($image): void {
            ProductImage::where('product_id', $image->product_id)->update(['is_primary' => false]);
            $image->update(['is_primary' => true]);
        });
    }

    /**
     * Move an image one place earlier (-1) or later (+1). Positions are renumbered 0, 1, 2...
     */
    public function move(ProductImage $image, int $direction): void
    {
        $ids = ProductImage::where('product_id', $image->product_id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->pluck('id')
            ->all();

        $index = array_search($image->id, $ids, true);
        $target = $index === false ? false : $index + ($direction < 0 ? -1 : 1);

        if ($index === false || $target < 0 || $target >= count($ids)) {
            return;
        }

        [$ids[$index], $ids[$target]] = [$ids[$target], $ids[$index]];

        DB::transaction(function () use ($ids): void {
            foreach ($ids as $position => $id) {
                ProductImage::whereKey($id)->update(['sort_order' => $position]);
            }
        });
    }

    public function delete(ProductImage $image): void
    {
        $wasPrimary = $image->is_primary;
        $productId = $image->product_id;

        Storage::disk(self::DISK)->delete($image->path);
        $image->delete();

        if ($wasPrimary) {
            $first = ProductImage::where('product_id', $productId)->orderBy('sort_order')->orderBy('id')->first();
            $first?->update(['is_primary' => true]);
        }
    }
}
