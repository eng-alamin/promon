<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\ProductStatus;
use App\Livewire\Admin\Products\ProductTable;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductTableTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    private function makeProduct(string $name, ?Category $category = null, array $extra = []): Product
    {
        $category ??= Category::firstOrCreate(['slug' => 'supplements'], ['name' => 'Supplements']);

        return Product::create(array_merge([
            'category_id' => $category->id,
            'name' => $name,
            'slug' => str($name)->slug()->toString(),
            'price' => 10,
        ], $extra));
    }

    public function test_it_lists_products_with_price_in_pounds(): void
    {
        $this->makeProduct('Vitamin C', null, ['price' => 12.5]);

        Livewire::test(ProductTable::class)
            ->assertSee('Vitamin C')
            ->assertSee('£12.50');
    }

    public function test_it_shows_an_empty_state(): void
    {
        Livewire::test(ProductTable::class)->assertSee('No products yet');
    }

    public function test_search_filters_by_name_and_sku(): void
    {
        $this->makeProduct('Vitamin C', null, ['sku' => 'VC-100']);
        $this->makeProduct('Fish Oil');

        Livewire::test(ProductTable::class)
            ->set('search', 'Vitamin')
            ->assertSee('Vitamin C')
            ->assertDontSee('Fish Oil')
            ->set('search', 'VC-100')
            ->assertSee('Vitamin C')
            ->assertDontSee('Fish Oil');
    }

    public function test_it_filters_by_category(): void
    {
        $drinks = Category::create(['name' => 'Drinks', 'slug' => 'drinks']);
        $this->makeProduct('Vitamin C');
        $this->makeProduct('Orange Juice', $drinks);

        Livewire::test(ProductTable::class)
            ->set('categoryFilter', (string) $drinks->id)
            ->assertSee('Orange Juice')
            ->assertDontSee('Vitamin C');
    }

    public function test_it_filters_by_stock_status(): void
    {
        $this->makeProduct('Vitamin C');
        $this->makeProduct('Fish Oil', null, ['status' => ProductStatus::AdvanceOrder->value]);

        Livewire::test(ProductTable::class)
            ->set('statusFilter', ProductStatus::AdvanceOrder->value)
            ->assertSee('Fish Oil')
            ->assertDontSee('Vitamin C');
    }

    public function test_an_unknown_status_filter_is_ignored(): void
    {
        $this->makeProduct('Vitamin C');

        Livewire::test(ProductTable::class)
            ->set('statusFilter', 'bogus')
            ->assertSee('Vitamin C');
    }

    public function test_it_toggles_visibility(): void
    {
        $product = $this->makeProduct('Vitamin C', null, ['is_active' => true]);

        Livewire::test(ProductTable::class)->call('toggleActive', $product->id);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'is_active' => false]);
    }

    public function test_it_soft_deletes_a_product(): void
    {
        $product = $this->makeProduct('Vitamin C');

        Livewire::test(ProductTable::class)
            ->call('confirmDelete', $product->id)
            ->assertSet('deletingId', $product->id)
            ->call('delete')
            ->assertSet('deletingId', null)
            ->assertDispatched('toast')
            ->assertDontSee('Vitamin C');

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_cancel_keeps_the_product(): void
    {
        $product = $this->makeProduct('Vitamin C');

        Livewire::test(ProductTable::class)
            ->call('confirmDelete', $product->id)
            ->call('cancelDelete')
            ->assertSet('deletingId', null);

        $this->assertNotSoftDeleted('products', ['id' => $product->id]);
    }
}
