<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiry_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enquiry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('quantity');
            // Price snapshot: the price shown to the customer at the time of the enquiry.
            $table->decimal('unit_price', 10, 2);
            $table->timestamps();

            $table->unique(['enquiry_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiry_items');
    }
};
