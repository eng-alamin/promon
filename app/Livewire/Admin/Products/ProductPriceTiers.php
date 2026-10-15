<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use App\Models\ProductPriceTier;
use App\Services\Admin\ProductPriceTierService;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ProductPriceTiers extends Component
{
    #[Locked]
    public int $productId;

    #[Locked]
    public ?int $editingId = null;

    public string $min_qty = '';

    public string $price = '';

    public function mount(Product $product): void
    {
        $this->productId = $product->id;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return [
            'min_qty' => [
                'required',
                'integer',
                'min:2',
                'max:1000000',
                Rule::unique('product_price_tiers', 'min_qty')
                    ->where('product_id', $this->productId)
                    ->ignore($this->editingId),
            ],
            'price' => ['required', 'numeric', 'decimal:0,2', 'min:0.01', 'max:99999999.99'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'min_qty.min' => 'The quantity must be 2 or more. The base price already covers 1 unit.',
            'min_qty.unique' => 'This product already has a price for that quantity.',
            'price.decimal' => 'The price may have at most 2 decimal places.',
            'price.min' => 'The price must be more than 0.',
        ];
    }

    public function save(ProductPriceTierService $tiers): void
    {
        $this->min_qty = trim($this->min_qty);
        $this->price = trim($this->price);

        $product = Product::findOrFail($this->productId);

        if ($this->editingId === null && $tiers->count($product) >= ProductPriceTierService::MAX_TIERS) {
            $this->addError('min_qty', 'A product can have at most ' . ProductPriceTierService::MAX_TIERS . ' price tiers.');

            return;
        }

        $data = $this->validate();

        $tier = $this->editingId !== null ? $this->tier($this->editingId) : null;
        $tiers->save($product, $tier, $data);

        $message = $tier === null ? 'Price tier added.' : 'Price tier updated.';

        $this->resetForm();
        $this->dispatch('toast', message: $message);
    }

    public function edit(int $id): void
    {
        $tier = $this->tier($id);

        $this->resetForm();
        $this->editingId = $tier->id;
        $this->min_qty = (string) $tier->min_qty;
        $this->price = (string) $tier->price;
    }

    public function cancelEdit(): void
    {
        $this->resetForm();
    }

    public function delete(int $id, ProductPriceTierService $tiers): void
    {
        $tiers->delete($this->tier($id));

        if ($this->editingId === $id) {
            $this->resetForm();
        }

        $this->dispatch('toast', message: 'Price tier deleted.');
    }

    public function render(): View
    {
        $product = Product::findOrFail($this->productId);

        return view('livewire.admin.products.product-price-tiers', [
            'basePrice' => (string) $product->price,
            'tiers' => ProductPriceTier::where('product_id', $this->productId)->orderBy('min_qty')->get(),
            'maxTiers' => ProductPriceTierService::MAX_TIERS,
        ]);
    }

    /**
     * Only price tiers of this product can be changed.
     */
    private function tier(int $id): ProductPriceTier
    {
        return ProductPriceTier::where('product_id', $this->productId)->find($id) ?? abort(404);
    }

    private function resetForm(): void
    {
        $this->reset(['min_qty', 'price', 'editingId']);
        $this->resetValidation();
    }
}
