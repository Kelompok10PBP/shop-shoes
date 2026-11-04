<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'shipping_address',
        'shipping_method',
        'shipping_fee',
        'tax_amount',
        'subtotal',
        'discount_amount',
        'total_amount',
        'coupon_id',
        'coupon_code',
        'status',
        'notes',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'shipping_fee' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function getCustomerNameAttribute(): string
    {
        return $this->user ? $this->user->name : ($this->guest_name ?: 'Guest Customer');
    }

    public function getCustomerEmailAttribute(): string
    {
        return $this->user ? $this->user->email : ($this->guest_email ?: '-');
    }
}
