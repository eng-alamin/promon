<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Products;

use App\Enums\ProductStatus;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\Admin\ProductService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ProductForm extends Component
{
    /** Optional text fields: an empty box is saved as NULL, not as an empty string. */
    private const NULLABLE_FIELDS = ['sku', 'description', 'pack_size', 'origin_country', 'shelf_life', 'bd_approval', 'moq'];

    #[Locked]
    public ?int $productId = null;

    public string $category_id = '';

    public string $brand_id = '';

    public string $name = '';

    public string $slug = '';

    public ?string $sku = null;

    public ?string $description = null;

    public string $price = '';

    public ?string $moq = null;

    public ?string $pack_size = null;

    public string $status = 'in_stock';

    public ?string $origin_country = null;

    public ?string $shelf_life = null;

    public ?string $bd_approval = null;

    public bool $is_popular = false;

    public bool $is_new = false;

    public bool $is_active = true;

    public string $sort_order = '0';

    public function mount(?Product $product = null): void
    {
        if ($product === null || ! $product->exists) {
            return;
        }

        $this->productId = $product->id;
        $this->category_id = (string) $product->category_id;
        $this->brand_id = $product->brand_id !== null ? (string) $product->brand_id : '';
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->sku = $product->sku;
        $this->description = $product->description;
        $this->price = (string) $product->price;
        $this->moq = $product->moq !== null ? (string) $product->moq : null;
        $this->pack_size = $product->pack_size;
        $this->status = $product->status->value;
        $this->origin_country = $product->origin_country;
        $this->shelf_life = $product->shelf_life;
        $this->bd_approval = $product->bd_approval;
        $this->is_popular = $product->is_popular;
        $this->is_new = $product->is_new;
        $this->is_active = $product->is_active;
        $this->sort_order = (string) $product->sort_order;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return [
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'brand_id' => ['nullable', Rule::exists('brands', 'id')],
            'name' => ['required', 'string', 'max:200'],
            'slug' => [
                'required',
                'string',
                'max:220',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                // Soft deleted products still hold their slug, so the check includes them.
                Rule::unique('products', 'slug')->ignore($this->productId),
            ],
            'sku' => ['nullable', 'string', 'max:60', Rule::unique('products', 'sku')->ignore($this->productId)],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:99999999.99'],
            'moq' => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'pack_size' => ['nullable', 'string', 'max:80'],
            'status' => ['required', Rule::enum(ProductStatus::class)],
            'origin_country' => ['nullable', 'string', 'max:80'],
            'shelf_life' => ['nullable', 'string', 'max:80'],
            'bd_approval' => ['nullable', 'string', 'max:160'],
            'is_popular' => ['boolean'],
            'is_new' => ['boolean'],
            'is_active' => ['boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:65535'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'category_id.required' => 'Please choose a category.',
            'slug.required' => 'The slug is required. Type one using English letters and numbers.',
            'slug.regex' => 'The slug may contain lowercase letters, numbers and single hyphens only.',
            'price.decimal' => 'The price may have at most 2 decimal places.',
            'status.enum' => 'Please choose a valid stock status.',
        ];
    }

    public function save(ProductService $products): void
    {
        $this->prepareInput();

        $data = $this->validate();

        $product = $this->productId !== null ? Product::findOrFail($this->productId) : null;
        $saved = $products->save($product, $data);

        if ($product === null) {
            // Images and quantity prices need a saved product, so continue on its edit page.
            session()->flash('success', 'Product created. You can now add images and quantity prices.');
            $this->redirectRoute('admin.products.edit', ['product' => $saved->id]);

            return;
        }

        session()->flash('success', 'Product updated.');
        $this->redirectRoute('admin.products.index');
    }

    public function render(): View
    {
        // Only active categories and brands can be picked, plus the one this product already uses.
        $categories = Category::query()
            ->where(fn ($query) => $query->where('is_active', true)->orWhere('id', (int) $this->category_id))
            ->ordered()
            ->get();

        $brands = Brand::query()
            ->where(fn ($query) => $query->where('is_active', true)->orWhere('id', (int) $this->brand_id))
            ->orderBy('name')
            ->get();

        return view('livewire.admin.products.product-form', [
            'categories' => $categories,
            'brands' => $brands,
            'statuses' => ProductStatus::cases(),
        ]);
    }

    private function prepareInput(): void
    {
        foreach (self::NULLABLE_FIELDS as $field) {
            $value = $this->{$field} === null ? '' : trim($this->{$field});
            $this->{$field} = $value === '' ? null : $value;
        }

        $this->name = trim($this->name);
        $this->price = trim($this->price);
        $this->slug = Str::slug($this->slug !== '' ? $this->slug : $this->name);
    }
}
