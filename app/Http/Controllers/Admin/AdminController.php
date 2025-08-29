<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $shops = Shop::withCount(['products', 'orders'])->get();
        $totalShops = $shops->count();
        $activeShops = $shops->where('is_active', true)->count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        
        return view('admin.dashboard', compact(
            'shops', 
            'totalShops', 
            'activeShops', 
            'totalProducts', 
            'totalOrders'
        ));
    }
}
