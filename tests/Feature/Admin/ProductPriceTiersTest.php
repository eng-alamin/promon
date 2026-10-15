<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Products\ProductPriceTiers;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPriceTier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductPriceTiersTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());

        $category = Category::create(['name' => 'Supplements', 'slug' => 'supplements']);
        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Vitamin C',
            'slug' => 'vitamin-c',
            'price' => 10,
        ])->fresh();
    }

    private function tiersComponent()
    {
        return Livewire::test(ProductPriceTiers::class, ['product' => $this->product]);
    }

    public function test_it_adds_a_price_tier(): void
    {
        $this->tiersComponent()
            ->set('min_qty', '12')
            ->set('price', '9.5')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('min_qty', '')
            ->assertDispatched('toast')
            ->assertSee('£9.50');

        $tier = ProductPriceTier::where('product_id', $this->product->id)->firstOrFail();

        $this->assertSame(12, $tier->min_qty);
        $this->assertSame('9.50', $tier->price);
    }

    public function test_required_fields(): void
    {
        $this->tiersComponent()
            ->call('save')
            ->assertHasErrors(['min_qty' => 'required', 'price' => 'required']);
    }

    public function test_quantity_must_be_two_or_more(): void
    {
        $this->tiersComponent()
            ->set('min_qty', '1')
            ->set('price', '9')
            ->call('save')
            ->assertHasErrors(['min_qty' => 'min']);
    }

    public function test_price_must_be_a_valid_amount(): void
    {
        $component = $this->tiersComponent()->set('min_qty', '12');

        $component->set('price', 'abc')->call('save')->assertHasErrors(['price' => 'numeric']);
        $component->set('price', '0')->call('save')->assertHasErrors(['price' => 'min']);
        $component->set('price', '1.234')->call('save')->assertHasErrors(['price' => 'decimal']);
    }

    public function test_the_same_quantity_cannot_be_added_twice(): void
    {
        ProductPriceTier::create(['product_id' => $this->product->id, 'min_qty' => 12, 'price' => 9]);

        $this->tiersComponent()
            ->set('min_qty', '12')
            ->set('price', '8')
            ->call('save')
            ->assertHasErrors(['min_qty' => 'unique']);
    }

    public function test_another_product_can_use_the_same_quantity(): void
    {
        $other = Product::create([
            'category_id' => $this->product->category_id,
            'name' => 'Fish Oil',
            'slug' => 'fish-oil',
            'price' => 8,
        ])->fresh();
        ProductPriceTier::create(['product_id' => $other->id, 'min_qty' => 12, 'price' => 7]);

        $this->tiersComponent()
            ->set('min_qty', '12')
            ->set('price', '9')
            ->call('save')
            ->assertHasNoErrors();
    }

    public function test_it_edits_a_tier_and_keeps_its_own_quantity(): void
    {
        $tier = ProductPriceTier::create(['product_id' => $this->product->id, 'min_qty' => 12, 'price' => 9]);

        $this->tiersComponent()
            ->call('edit', $tier->id)
            ->assertSet('min_qty', '12')
            ->assertSet('price', '9.00')
            ->set('price', '8.25')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('editingId', null);

        $this->assertSame('8.25', $tier->fresh()->price);
        $this->assertSame(1, ProductPriceTier::count());
    }

    public function test_it_deletes_a_tier(): void
    {
        $tier = ProductPriceTier::create(['product_id' => $this->product->id, 'min_qty' => 12, 'price' => 9]);

        $this->tiersComponent()->call('delete', $tier->id)->assertDispatched('toast');

        $this->assertDatabaseMissing('product_price_tiers', ['id' => $tier->id]);
    }

    public function test_it_stops_at_ten_tiers(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            ProductPriceTier::create(['product_id' => $this->product->id, 'min_qty' => $i + 1, 'price' => 9]);
        }

        $this->tiersComponent()
            ->set('min_qty', '50')
            ->set('price', '5')
            ->call('save')
            ->assertHasErrors('min_qty');

        $this->assertSame(10, ProductPriceTier::count());
    }

    public function test_tiers_of_another_product_cannot_be_changed(): void
    {
        $other = Product::create([
            'category_id' => $this->product->category_id,
            'name' => 'Fish Oil',
            'slug' => 'fish-oil',
            'price' => 8,
        ])->fresh();
        $foreign = ProductPriceTier::create(['product_id' => $other->id, 'min_qty' => 12, 'price' => 7]);

        $this->tiersComponent()->call('delete', $foreign->id)->assertNotFound();

        $this->assertDatabaseHas('product_price_tiers', ['id' => $foreign->id]);
    }
}
