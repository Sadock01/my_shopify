<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'preview_image',
        'theme_colors',
        'layout_options',
        'is_active'
    ];

    protected $casts = [
        'theme_colors' => 'array',
        'layout_options' => 'array',
        'is_active' => 'boolean'
    ];

    public function shops()
    {
        return $this->hasMany(Shop::class, 'template_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
