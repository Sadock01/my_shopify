
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopFrontendController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('welcome');
});

// Routes d'authentification
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Routes Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    
    // Gestion des boutiques
    Route::resource('shops', App\Http\Controllers\Admin\ShopController::class);
    Route::get('/shops/{shop}/manage', [App\Http\Controllers\Admin\ShopController::class, 'manage'])->name('shops.manage');
    
    // Gestion des moyens de paiement
Route::resource('shops.payment-methods', App\Http\Controllers\Admin\PaymentMethodController::class);

// Gestion des catégories
Route::resource('shops.categories', App\Http\Controllers\Admin\CategoryController::class);

// Gestion des produits
Route::resource('shops.products', App\Http\Controllers\Admin\ProductController::class);

// Gestion des témoignages
Route::resource('shops.testimonials', App\Http\Controllers\Admin\TestimonialController::class);

// Gestion des utilisateurs
Route::resource('users', App\Http\Controllers\Admin\UserController::class);
Route::post('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
Route::get('/users/search', [App\Http\Controllers\Admin\UserController::class, 'search'])->name('users.search');

// Gestion des templates
Route::get('/templates', [App\Http\Controllers\Admin\TemplateController::class, 'index'])->name('templates.index');
    Route::get('/templates/{template}/preview', [App\Http\Controllers\Admin\TemplateController::class, 'preview'])->name('templates.preview');
    Route::get('/templates/{template}/customize', [App\Http\Controllers\Admin\TemplateController::class, 'customize'])->name('templates.customize');
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
    
    // Routes protégées par authentification
    Route::middleware(['auth'])->group(function () {
        Route::post('/checkout', [ShopFrontendController::class, 'checkout'])->name('shop.checkout');
        Route::get('/payment-info', [ShopFrontendController::class, 'paymentInfo'])->name('shop.payment-info');
    });
});

// Routes pour les boutiques sans domaine personnalisé (avec slug)
Route::prefix('shop/{shop:slug}')->group(function () {
    Route::get('/', [ShopFrontendController::class, 'home'])->name('shop.home.slug');
    Route::get('/products', [ShopFrontendController::class, 'products'])->name('shop.products.slug');
    Route::get('/products/{productId}', [ShopFrontendController::class, 'product'])->name('shop.product.slug');
    Route::get('/category/{categorySlug}', [ShopFrontendController::class, 'category'])->name('shop.category.slug');
    Route::get('/cart', [ShopFrontendController::class, 'cart'])->name('shop.cart.slug');
    
    // Routes protégées par authentification
    Route::middleware(['auth'])->group(function () {
        Route::post('/checkout', [ShopFrontendController::class, 'checkout'])->name('shop.checkout.slug');
        Route::get('/payment-info', [ShopFrontendController::class, 'paymentInfo'])->name('shop.payment-info.slug');
    });
});
