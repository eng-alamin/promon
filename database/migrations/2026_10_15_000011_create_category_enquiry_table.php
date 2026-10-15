<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_enquiry', function (Blueprint $table) {
            $table->foreignId('enquiry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();

            $table->primary(['enquiry_id', 'category_id']);
        });

        // Old enquiries: link each one to the categories of the products it listed,
        // so no enquiry loses its category when the product list is removed.
        if (Schema::hasTable('enquiry_items') && Schema::hasTable('products')) {
            DB::table('category_enquiry')->insertUsing(
                ['enquiry_id', 'category_id'],
                DB::table('enquiry_items')
                    ->join('products', 'products.id', '=', 'enquiry_items.product_id')
                    ->select('enquiry_items.enquiry_id', 'products.category_id')
                    ->distinct()
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('category_enquiry');
    }
};
