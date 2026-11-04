<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'subtitle',
        'description',
        'details',
        'base_price',
        'compare_at_price',
        'discount_type',
        'discount_value',
        'discount_start_at',
        'discount_end_at',
        'is_discount_active',
        'badge',
        'is_trending',
        'is_featured',
        'is_new',
        'status',
    ];

    protected $casts = [
        'details' => 'array',
        'base_price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'discount_start_at' => 'datetime',
        'discount_end_at' => 'datetime',
        'is_discount_active' => 'boolean',
        'is_trending' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'collection_product');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    /**
     * Compute final price after checking active discounts
     */
    public function getFinalPriceAttribute(): float
    {
        $price = (float) $this->base_price;

        if ($this->is_discount_active) {
            $now = now();
            $starts = $this->discount_start_at ? $this->discount_start_at <= $now : true;
            $ends = $this->discount_end_at ? $this->discount_end_at >= $now : true;

            if ($starts && $ends) {
                if ($this->discount_type === 'percent') {
                    $discount = ($price * (float) $this->discount_value) / 100;
                    $price = max(0, $price - $discount);
                } elseif ($this->discount_type === 'fixed') {
                    $price = max(0, $price - (float) $this->discount_value);
                }
            }
        }

        return round($price, 2);
    }

    /**
     * Effective discount percentage
     */
    public function getDiscountPercentAttribute(): ?int
    {
        if ($this->compare_at_price && $this->compare_at_price > $this->final_price) {
            return (int) round((($this->compare_at_price - $this->final_price) / $this->compare_at_price) * 100);
        }

        if ($this->is_discount_active && $this->discount_type === 'percent' && $this->discount_value > 0) {
            return (int) round($this->discount_value);
        }

        return null;
    }

    public function getAverageRatingAttribute(): float
    {
        return round((float) ($this->reviews()->avg('rating') ?: 5.0), 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function getTotalStockAttribute(): int
    {
        return (int) $this->variants()->sum('stock');
    }

    public function getDisplayImageAttribute(): string
    {
        $primary = $this->images->firstWhere('is_primary', true);
        if ($primary) {
            return $primary->image_path;
        }

        $first = $this->images->first();
        if ($first) {
            return $first->image_path;
        }

        return '/images/placeholder-shoe.png';
    }
}
