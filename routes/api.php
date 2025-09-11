<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ShopTemplateController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route simple de test
Route::get('/test', function () {
    return response()->json([
        'message' => 'API fonctionne correctement!',
        'status' => 'OK',
        'time' => now()->format('Y-m-d H:i:s')
    ]);
});


// Routes pour les templates de boutiques
Route::apiResource('templates', ShopTemplateController::class);

// Routes pour les boutiques
Route::apiResource('shops', ShopController::class);

// Routes pour les produits
Route::apiResource('products', ProductController::class);

// Routes pour les commandes
Route::apiResource('orders', OrderController::class);

// Routes pour les catégories
Route::apiResource('categories', CategoryController::class);

// Route pour obtenir les produits d'une boutique spécifique
Route::get('/shops/{shop}/products', [ProductController::class, 'getShopProducts']);

// Route pour obtenir les catégories d'une boutique
Route::get('/shops/{shop}/categories', function ($shop) {
    return response()->json([
        'categories' => $shop->products()->with('category')->get()->pluck('category')->unique()
    ]);
});

// Route pour filtrer les produits par catégorie
Route::get('/shops/{shop}/products/category/{category}', [ProductController::class, 'getProductsByCategory']);

// Route pour obtenir une boutique par domaine
Route::get('/shop/by-domain', [ShopController::class, 'getByDomain']);

// Route pour obtenir les commandes d'une boutique
Route::get('/shops/{shop}/orders', [OrderController::class, 'getShopOrders']);

// Route pour passer une commande
Route::post('/shops/{shop}/orders', [OrderController::class, 'store']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
