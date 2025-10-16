<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopFrontendController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ShopAuthController;
use Illuminate\Support\Facades\Auth;

// ========================================
// ROUTES D'ACCUEIL ET GÉNÉRALES
// ========================================

// Route d'accueil principale
Route::get('/', function () {
    // Si l'utilisateur est connecté, le rediriger vers une boutique active ou le dashboard admin
    if (Auth::check()) {
        if (Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        
        // Rediriger vers la première boutique active
        $activeShop = \App\Models\Shop::active()->first();
        if ($activeShop) {
            return redirect()->route('shop.home.slug', ['shop' => $activeShop->slug]);
        }
    }
    
    // Sinon, afficher la page d'accueil
    return view('welcome');
})->name('home');

// Route de démonstration pour accéder aux boutiques
Route::get('/demo', function () {
    $shops = \App\Models\Shop::with('template')->active()->get();
    return view('demo', compact('shops'));
})->name('demo');

// ========================================
// ROUTES D'AUTHENTIFICATION ADMIN
// ========================================

// Routes de connexion/inscription pour le dashboard admin
Route::get('/admin/login', function () {
    return view('auth.login');
})->name('admin.login');

Route::get('/admin/register', function () {
    return view('auth.register');
})->name('admin.register');

Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.post');
Route::post('/admin/register', [RegisterController::class, 'register'])->name('admin.register.post');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

// ========================================
// ROUTES ADMIN (PROTÉGÉES)
// ========================================

Route::middleware(['auth', 'admin', 'refresh.session'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    
    // Gestion des boutiques
    Route::resource('shops', App\Http\Controllers\Admin\ShopController::class);
    Route::get('/shops/{shop}/manage', [App\Http\Controllers\Admin\ShopController::class, 'manage'])->name('shops.manage');
    Route::post('/shops/{shop}/toggle-status', [App\Http\Controllers\Admin\ShopController::class, 'toggleStatus'])->name('shops.toggle-status');
    
    // Gestion des moyens de paiement
    Route::resource('shops.payment-methods', App\Http\Controllers\Admin\PaymentMethodController::class);
    
    // Gestion des informations de paiement
    Route::get('/shops/{shop}/payment-info', [App\Http\Controllers\Admin\PaymentInfoController::class, 'index'])->name('payment-info.index');
    Route::put('/shops/{shop}/payment-info', [App\Http\Controllers\Admin\PaymentInfoController::class, 'update'])->name('payment-info.update');

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

// ========================================
// ROUTES BOUTIQUES (AVEC DOMAINE PERSONNALISÉ)
// ========================================

Route::middleware(['detect.shop'])->group(function () {
    Route::get('/', [ShopFrontendController::class, 'home'])->name('shop.home');
    Route::get('/products', [ShopFrontendController::class, 'products'])->name('shop.products');
    Route::get('/products/{productId}', [ShopFrontendController::class, 'product'])->name('shop.product');
    Route::get('/category/{categorySlug}', [ShopFrontendController::class, 'category'])->name('shop.category');
    Route::get('/cart', [ShopFrontendController::class, 'cart'])->name('shop.cart');
    
    // Routes d'authentification avec template de boutique
    Route::get('/login', [ShopAuthController::class, 'showLogin'])->name('shop.login');
    Route::post('/login', [ShopAuthController::class, 'login'])->name('shop.login.post');
    Route::get('/register', [ShopAuthController::class, 'showRegister'])->name('shop.register');
    Route::post('/register', [ShopAuthController::class, 'register'])->name('shop.register.post');
    Route::post('/logout', [ShopAuthController::class, 'logout'])->name('shop.logout');
    
    // Routes multi-boutiques
    Route::post('/switch-shop/{targetShop}', [ShopAuthController::class, 'switchShop'])->name('shop.switch');
    Route::post('/logout-shop/{targetShop}', [ShopAuthController::class, 'logoutFromShop'])->name('shop.logout.specific');
    
    // Routes protégées par authentification
    Route::middleware(['auth', 'refresh.session'])->group(function () {
        Route::post('/checkout', [ShopFrontendController::class, 'checkout'])->name('shop.checkout');
        Route::get('/payment-info', [ShopFrontendController::class, 'paymentInfo'])->name('shop.payment-info');
    });
    
    // Route pour mettre à jour le compteur du panier
    Route::get('/cart-count', [ShopFrontendController::class, 'getCartCount'])->name('shop.cart-count');
    
    // Route pour synchroniser le panier localStorage vers la session
    Route::post('/cart-sync', [ShopFrontendController::class, 'syncCart'])->name('shop.cart-sync');
    
    // Route pour récupérer les informations des produits du panier
    Route::post('/cart-products', [ShopFrontendController::class, 'getCartProducts'])->name('shop.cart-products');
});

// ========================================
// ROUTES BOUTIQUES (AVEC SLUG)
// ========================================

Route::prefix('shop/{shop:slug}')->group(function () {
    Route::get('/', [ShopFrontendController::class, 'home'])->name('shop.home.slug');
    Route::get('/products', [ShopFrontendController::class, 'products'])->name('shop.products.slug');
    Route::get('/products/{productId}', [ShopFrontendController::class, 'product'])->name('shop.product.slug');
    Route::get('/category/{categorySlug}', [ShopFrontendController::class, 'category'])->name('shop.category.slug');
    Route::get('/cart', [ShopFrontendController::class, 'cart'])->name('shop.cart.slug');
    
    // Routes d'authentification avec template de boutique
    Route::get('/login', [ShopAuthController::class, 'showLogin'])->name('shop.login.slug');
    Route::post('/login', [ShopAuthController::class, 'login'])->name('shop.login.slug.post');
    Route::get('/register', [ShopAuthController::class, 'showRegister'])->name('shop.register.slug');
    Route::post('/register', [ShopAuthController::class, 'register'])->name('shop.register.slug.post');
    Route::post('/logout', [ShopAuthController::class, 'logout'])->name('shop.logout.slug');
    
    // Routes multi-boutiques
    Route::post('/switch-shop/{targetShop}', [ShopAuthController::class, 'switchShop'])->name('shop.switch.slug');
    Route::post('/logout-shop/{targetShop}', [ShopAuthController::class, 'logoutFromShop'])->name('shop.logout.specific.slug');
    
    // Routes protégées par authentification
    Route::middleware(['auth', 'refresh.session'])->group(function () {
        Route::post('/checkout', [ShopFrontendController::class, 'checkout'])->name('shop.checkout.slug');
        Route::get('/payment-info', [ShopFrontendController::class, 'paymentInfo'])->name('shop.payment-info.slug');
    });
    
    // Route pour mettre à jour le compteur du panier
    Route::get('/cart-count', [ShopFrontendController::class, 'getCartCount'])->name('shop.cart-count.slug');
    
    // Route pour synchroniser le panier localStorage vers la session
    Route::post('/cart-sync', [ShopFrontendController::class, 'syncCart'])->name('shop.cart-sync.slug');
    
    // Route pour récupérer les informations des produits du panier
    Route::post('/cart-products', [ShopFrontendController::class, 'getCartProducts'])->name('shop.cart-products.slug');
});

// ========================================
// ROUTES UTILITAIRES
// ========================================

// Route pour vider le panier
Route::post('/clear-cart', [App\Http\Controllers\Auth\LoginController::class, 'clearCart'])->name('clear-cart');

// Routes pour la gestion des sessions
Route::get('/refresh-csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
})->name('refresh.csrf');

Route::get('/check-session', function () {
    if (Auth::check()) {
        return response()->json(['status' => 'active']);
    }
    return response()->json(['status' => 'expired'], 401);
})->name('check.session');