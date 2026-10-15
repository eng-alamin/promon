<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Products;

use App\Enums\ProductStatus;
use App\Livewire\Concerns\WithSearch;
use App\Models\Category;
use App\Models\Product;
use App\Services\Admin\ProductService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class ProductTable extends Component
{
    use WithPagination;
    use WithSearch;

    private const PER_PAGE = 10;

    public string $categoryFilter = '';

    public string $statusFilter = '';

    #[Locked]
    public ?int $deletingId = null;

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function toggleActive(int $id, ProductService $products): void
    {
        $products->toggleActive(Product::findOrFail($id));
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = Product::findOrFail($id)->id;
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
    }

    public function delete(ProductService $products): void
    {
        $product = $this->deletingId !== null ? Product::find($this->deletingId) : null;
        $this->deletingId = null;

        if ($product === null) {
            return;
        }

        $products->delete($product);

        $this->resetPage();
        $this->dispatch('toast', message: 'Product deleted.');
    }

    public function render(): View
    {
        $term = $this->likeTerm();

        $products = Product::query()
            ->with(['category', 'brand'])
            ->when(trim($this->search) !== '', function ($query) use ($term) {
                $query->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)
                        ->orWhere('sku', 'like', $term)
                        ->orWhere('slug', 'like', $term);
                });
            })
            ->when(ctype_digit($this->categoryFilter), fn ($query) => $query->where('category_id', (int) $this->categoryFilter))
            ->when(ProductStatus::tryFrom($this->statusFilter) !== null, fn ($query) => $query->where('status', $this->statusFilter))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(self::PER_PAGE);

        return view('livewire.admin.products.product-table', [
            'products' => $products,
            'categories' => Category::ordered()->get(),
            'statuses' => ProductStatus::cases(),
            'deletingProduct' => $this->deletingId !== null ? Product::find($this->deletingId) : null,
        ]);
    }
}
