<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('hero'); // 'hero', 'strip', etc.
            $table->string('pill_label')->default('New Collection 2025');
            $table->string('title')->default('Step Into Your');
            $table->string('title_highlight')->default('Best Pace');
            $table->text('subtitle')->nullable();
            $table->string('cta_text')->default('Shop Now');
            $table->string('cta_url')->default('/shop');
            $table->string('secondary_cta_text')->default('Explore Collections');
            $table->string('secondary_cta_url')->default('/collections');
            $table->string('image')->nullable();
            $table->string('tech_tag_text')->default('BioFoam Propulsion v2');
            $table->integer('discount_badge_percent')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('discount_text')->nullable();
            $table->string('image_1')->nullable();
            $table->string('image_2')->nullable();
            $table->string('cta_text')->default('Shop the Sale →');
            $table->string('cta_url')->default('/shop?sale=1');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->index();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });

        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('newsletter_subscribers');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('banners');
    }
};
