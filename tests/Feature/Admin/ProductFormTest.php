<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\ProductStatus;
use App\Livewire\Admin\Products\ProductForm;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductFormTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
        $this->category = Category::create(['name' => 'Supplements', 'slug' => 'supplements']);
    }

    private function makeProduct(array $extra = []): Product
    {
        return Product::create(array_merge([
            'category_id' => $this->category->id,
            'name' => 'Vitamin C',
            'slug' => 'vitamin-c',
            'price' => 10,
        ], $extra))->fresh();
    }

    public function test_it_creates_a_product_and_makes_the_slug_from_the_name(): void
    {
        Livewire::test(ProductForm::class)
            ->set('category_id', (string) $this->category->id)
            ->set('name', 'Vitamin C 1000mg')
            ->set('price', '12.5')
            ->set('moq', '24')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect();

        $product = Product::where('slug', 'vitamin-c-1000mg')->first();

        $this->assertNotNull($product);
        $this->assertSame('12.50', $product->price);
        $this->assertSame(24, $product->moq);
        $this->assertSame(ProductStatus::InStock, $product->status);
        $this->assertTrue($product->is_active);
    }

    public function test_empty_optional_fields_are_saved_as_null(): void
    {
        Livewire::test(ProductForm::class)
            ->set('category_id', (string) $this->category->id)
            ->set('name', 'Fish Oil')
            ->set('price', '8')
            ->set('sku', '  ')
            ->set('moq', '')
            ->set('brand_id', '')
            ->call('save')
            ->assertHasNoErrors();

        $product = Product::where('slug', 'fish-oil')->firstOrFail();

        $this->assertNull($product->sku);
        $this->assertNull($product->moq);
        $this->assertNull($product->brand_id);
    }

    public function test_it_saves_a_brand(): void
    {
        $brand = Brand::create(['name' => 'Holland', 'slug' => 'holland']);

        Livewire::test(ProductForm::class)
            ->set('category_id', (string) $this->category->id)
            ->set('brand_id', (string) $brand->id)
            ->set('name', 'Fish Oil')
            ->set('price', '8')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame($brand->id, Product::where('slug', 'fish-oil')->firstOrFail()->brand_id);
    }

    public function test_required_fields(): void
    {
        Livewire::test(ProductForm::class)
            ->call('save')
            ->assertHasErrors(['category_id' => 'required', 'name' => 'required', 'price' => 'required']);
    }

    public function test_category_must_exist(): void
    {
        Livewire::test(ProductForm::class)
            ->set('category_id', '9999')
            ->set('name', 'Fish Oil')
            ->set('price', '8')
            ->call('save')
            ->assertHasErrors(['category_id' => 'exists']);
    }

    public function test_price_must_be_a_valid_amount(): void
    {
        $component = Livewire::test(ProductForm::class)
            ->set('category_id', (string) $this->category->id)
            ->set('name', 'Fish Oil');

        $component->set('price', 'abc')->call('save')->assertHasErrors(['price' => 'numeric']);
        $component->set('price', '-1')->call('save')->assertHasErrors(['price' => 'min']);
        $component->set('price', '1.234')->call('save')->assertHasErrors(['price' => 'decimal']);
    }

    public function test_status_must_be_valid(): void
    {
        Livewire::test(ProductForm::class)
            ->set('category_id', (string) $this->category->id)
            ->set('name', 'Fish Oil')
            ->set('price', '8')
            ->set('status', 'bogus')
            ->call('save')
            ->assertHasErrors(['status']);
    }

    public function test_slug_and_sku_must_be_unique(): void
    {
        $this->makeProduct(['sku' => 'VC-1']);

        Livewire::test(ProductForm::class)
            ->set('category_id', (string) $this->category->id)
            ->set('name', 'Vitamin C')
            ->set('slug', 'vitamin-c')
            ->set('sku', 'VC-1')
            ->set('price', '8')
            ->call('save')
            ->assertHasErrors(['slug' => 'unique', 'sku' => 'unique']);
    }

    public function test_a_deleted_product_still_reserves_its_slug(): void
    {
        $this->makeProduct()->delete();

        Livewire::test(ProductForm::class)
            ->set('category_id', (string) $this->category->id)
            ->set('name', 'Vitamin C')
            ->set('price', '8')
            ->call('save')
            ->assertHasErrors(['slug' => 'unique']);
    }

    public function test_editing_loads_the_product_and_keeps_its_own_slug_and_sku(): void
    {
        $product = $this->makeProduct(['sku' => 'VC-1', 'moq' => 12]);

        Livewire::test(ProductForm::class, ['product' => $product])
            ->assertSet('name', 'Vitamin C')
            ->assertSet('price', '10.00')
            ->assertSet('moq', '12')
            ->assertSet('status', 'in_stock')
            ->call('save')
            ->assertHasNoErrors();
    }

    public function test_it_updates_a_product(): void
    {
        $product = $this->makeProduct();

        Livewire::test(ProductForm::class, ['product' => $product])
            ->set('name', 'Vitamin C Plus')
            ->set('slug', 'vitamin-c-plus')
            ->set('price', '15.75')
            ->set('status', ProductStatus::AdvanceOrder->value)
            ->set('is_popular', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.products.index'));

        $updated = $product->fresh();

        $this->assertSame('Vitamin C Plus', $updated->name);
        $this->assertSame('15.75', $updated->price);
        $this->assertSame(ProductStatus::AdvanceOrder, $updated->status);
        $this->assertTrue($updated->is_popular);
        $this->assertSame(1, Product::count());
    }
}
