<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'pill_label',
        'title',
        'title_highlight',
        'subtitle',
        'cta_text',
        'cta_url',
        'secondary_cta_text',
        'secondary_cta_url',
        'image',
        'tech_tag_text',
        'discount_badge_percent',
        'is_active',
    ];

    protected $casts = [
        'discount_badge_percent' => 'integer',
        'is_active' => 'boolean',
    ];
}
