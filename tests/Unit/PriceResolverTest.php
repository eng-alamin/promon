<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPriceTier;
use App\Services\PriceResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PriceResolverTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create(['name' => 'Supplements', 'slug' => 'supplements']);
        $this->product = Product::create(['category_id' => $category->id, 'name' => 'Vitamin C', 'slug' => 'vitamin-c', 'price' => 10]);
    }

    public function test_the_base_price_is_used_when_there_are_no_tiers(): void
    {
        $this->assertSame('10.00', (new PriceResolver())->unitPrice($this->product, 500));
    }

    public function test_the_biggest_reached_tier_is_used(): void
    {
        ProductPriceTier::create(['product_id' => $this->product->id, 'min_qty' => 12, 'price' => 9]);
        ProductPriceTier::create(['product_id' => $this->product->id, 'min_qty' => 50, 'price' => 8.5]);

        $resolver = new PriceResolver();

        $this->assertSame('10.00', $resolver->unitPrice($this->product, 11));
        $this->assertSame('9.00', $resolver->unitPrice($this->product, 12));
        $this->assertSame('9.00', $resolver->unitPrice($this->product, 49));
        $this->assertSame('8.50', $resolver->unitPrice($this->product, 50));
        $this->assertSame('8.50', $resolver->unitPrice($this->product, 1000));
    }
}
