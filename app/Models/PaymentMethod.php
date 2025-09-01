<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name',
        'type',
        'description',
        'icon',
        'details',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'details' => 'array',
        'is_active' => 'boolean'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
