<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->string('subtitle')->nullable();
            $table->longText('description')->nullable();
            $table->json('details')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->decimal('compare_at_price', 10, 2)->nullable();
            $table->string('discount_type')->default('none'); // 'none', 'percent', 'fixed'
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->timestamp('discount_start_at')->nullable();
            $table->timestamp('discount_end_at')->nullable();
            $table->boolean('is_discount_active')->default(false);
            $table->string('badge')->nullable(); // 'SALE', 'NEW', 'WATERPROOF', 'BEST SELLER', etc.
            $table->boolean('is_trending')->default(false)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_new')->default(false)->index();
            $table->string('status')->default('active')->index(); // 'active', 'draft'
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('collection_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['collection_id', 'product_id']);
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->string('alt_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('color_name')->nullable();
            $table->string('color_hex')->nullable();
            $table->string('size'); // US size e.g. '8.5', '9.0', '10.0'
            $table->string('sku')->unique();
            $table->integer('stock')->default(0);
            $table->decimal('price_override', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('collection_product');
        Schema::dropIfExists('products');
        Schema::dropIfExists('collections');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('categories');
    }
};
