<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use App\Models\ProductImage;
use App\Services\Admin\ProductImageService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductImages extends Component
{
    use WithFileUploads;

    #[Locked]
    public int $productId;

    /** @var array<int, mixed> */
    public array $uploads = [];

    public function mount(Product $product): void
    {
        $this->productId = $product->id;
    }

    /**
     * Runs as soon as the browser finishes uploading the chosen files.
     */
    public function updatedUploads(): void
    {
        $files = $this->uploads;
        $this->reset('uploads');
        $this->resetErrorBag('uploads');

        $images = app(ProductImageService::class);
        $product = Product::findOrFail($this->productId);
        $saved = 0;

        foreach ($files as $file) {
            if ($images->count($product) >= ProductImageService::MAX_IMAGES) {
                $this->addError('uploads', 'A product can have at most ' . ProductImageService::MAX_IMAGES . ' images.');
                break;
            }

            $validator = Validator::make(
                ['file' => $file],
                ['file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']],
                [],
                ['file' => $file->getClientOriginalName()]
            );

            if ($validator->fails()) {
                $this->addError('uploads', $validator->errors()->first('file') . ' Use JPG, PNG or WebP, up to 2 MB.');

                continue;
            }

            $images->store($product, $file);
            $saved++;
        }

        if ($saved > 0) {
            $this->dispatch('toast', message: $saved === 1 ? 'Image uploaded.' : "{$saved} images uploaded.");
        }
    }

    public function setPrimary(int $id, ProductImageService $images): void
    {
        $images->setPrimary($this->image($id));
    }

    public function moveUp(int $id, ProductImageService $images): void
    {
        $images->move($this->image($id), -1);
    }

    public function moveDown(int $id, ProductImageService $images): void
    {
        $images->move($this->image($id), 1);
    }

    public function delete(int $id, ProductImageService $images): void
    {
        $images->delete($this->image($id));

        $this->dispatch('toast', message: 'Image deleted.');
    }

    public function render(): View
    {
        return view('livewire.admin.products.product-images', [
            'images' => ProductImage::where('product_id', $this->productId)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
            'maxImages' => ProductImageService::MAX_IMAGES,
        ]);
    }

    /**
     * Only images of this product can be changed.
     */
    private function image(int $id): ProductImage
    {
        return ProductImage::where('product_id', $this->productId)->find($id) ?? abort(404);
    }
}
