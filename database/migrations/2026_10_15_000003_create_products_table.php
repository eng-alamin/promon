<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 200);
            $table->string('slug', 220)->unique();
            $table->string('sku', 60)->nullable()->unique();
            $table->text('description')->nullable();
            // Base price in GBP (pounds). Quantity based prices live in product_price_tiers.
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('moq')->nullable();
            $table->string('pack_size', 80)->nullable();
            $table->string('status', 20)->default('in_stock');
            $table->string('origin_country', 80)->nullable();
            $table->string('shelf_life', 80)->nullable();
            $table->string('bd_approval', 160)->nullable();
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id', 'is_active']);
            $table->index(['is_active', 'is_popular']);
            $table->index(['is_active', 'is_new']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
