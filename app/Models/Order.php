<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'user_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'items',
        'total_amount',
        'status',
        'notes',
        'payment_proof'
    ];

    protected $casts = [
        'items' => 'array',
        'total_amount' => 'decimal:2'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateOrderNumber()
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
    }
}
