
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopFrontendController;

Route::get('/', function () {
    return view('welcome');
});

// Route de démonstration pour accéder aux boutiques
Route::get('/demo', function () {
    $shops = \App\Models\Shop::with('template')->active()->get();
    return view('demo', compact('shops'));
})->name('demo');

// Routes pour les boutiques (avec domaine personnalisé)
Route::middleware(['detect.shop'])->group(function () {
    Route::get('/', [ShopFrontendController::class, 'home'])->name('shop.home');
    Route::get('/products', [ShopFrontendController::class, 'products'])->name('shop.products');
    Route::get('/products/{productId}', [ShopFrontendController::class, 'product'])->name('shop.product');
    Route::get('/category/{categorySlug}', [ShopFrontendController::class, 'category'])->name('shop.category');
    Route::get('/cart', [ShopFrontendController::class, 'cart'])->name('shop.cart');
    Route::post('/checkout', [ShopFrontendController::class, 'checkout'])->name('shop.checkout');
    Route::get('/payment-info', [ShopFrontendController::class, 'paymentInfo'])->name('shop.payment-info');
});

// Routes pour les boutiques sans domaine personnalisé (avec slug)
Route::prefix('shop/{shop:slug}')->group(function () {
    Route::get('/', [ShopFrontendController::class, 'home'])->name('shop.home.slug');
    Route::get('/products', [ShopFrontendController::class, 'products'])->name('shop.products.slug');
    Route::get('/products/{productId}', [ShopFrontendController::class, 'product'])->name('shop.product.slug');
    Route::get('/category/{categorySlug}', [ShopFrontendController::class, 'category'])->name('shop.category.slug');
    Route::get('/cart', [ShopFrontendController::class, 'cart'])->name('shop.cart.slug');
    Route::post('/checkout', [ShopFrontendController::class, 'checkout'])->name('shop.checkout.slug');
    Route::get('/payment-info', [ShopFrontendController::class, 'paymentInfo'])->name('shop.payment-info.slug');
});
