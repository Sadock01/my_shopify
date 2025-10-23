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
use Illuminate\Http\Request;

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
    Route::get('/users/search', [App\Http\Controllers\Admin\UserController::class, 'search'])->name('users.search');
    Route::post('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);

    // Gestion des commandes
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
    Route::patch('/orders/{order}/update-status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('/orders/{order}/view-proof', [App\Http\Controllers\Admin\OrderController::class, 'viewPaymentProof'])->name('orders.view-proof');
    Route::get('/orders/{order}/download-proof', [App\Http\Controllers\Admin\OrderController::class, 'downloadPaymentProof'])->name('orders.download-proof');
    Route::get('/shops/{shop}/orders', [App\Http\Controllers\Admin\OrderController::class, 'shopOrders'])->name('shops.orders');

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
        
        // Routes pour la preuve de paiement
        Route::get('/upload-payment-proof', [App\Http\Controllers\PaymentProofController::class, 'showUploadForm'])->name('shop.upload-payment-proof');
        Route::post('/upload-payment-proof', [App\Http\Controllers\PaymentProofController::class, 'uploadProof'])->name('shop.upload-payment-proof.post');
        Route::get('/payment-success', [App\Http\Controllers\PaymentProofController::class, 'paymentSuccess'])->name('shop.payment-success');
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
        
        // Routes pour la preuve de paiement
        Route::get('/upload-payment-proof', [App\Http\Controllers\PaymentProofController::class, 'showUploadForm'])->name('shop.upload-payment-proof.slug');
        Route::post('/upload-payment-proof', [App\Http\Controllers\PaymentProofController::class, 'uploadProof'])->name('shop.upload-payment-proof.slug.post');
        Route::get('/payment-success', [App\Http\Controllers\PaymentProofController::class, 'paymentSuccess'])->name('shop.payment-success.slug');
    });
    
    // Route pour mettre à jour le compteur du panier
    Route::get('/cart-count', [ShopFrontendController::class, 'getCartCount'])->name('shop.cart-count.slug');
    
    // Route pour synchroniser le panier localStorage vers la session
    Route::post('/cart-sync', [ShopFrontendController::class, 'syncCart'])->name('shop.cart-sync.slug');
    
    // Route pour récupérer les informations des produits du panier
    Route::post('/cart-products', [ShopFrontendController::class, 'getCartProducts'])->name('shop.cart-products.slug');
});

// ========================================
// ROUTES DE TEST (À SUPPRIMER EN PRODUCTION)
// ========================================

// Route de test pour vérifier que le système fonctionne
Route::get('/test-payment-proof', function () {
    // Créer un utilisateur de test s'il n'existe pas
    $user = \App\Models\User::first();
    if (!$user) {
        $user = \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
    }
    
    // Créer une boutique de test s'il n'en existe pas
    $shop = \App\Models\Shop::first();
    if (!$shop) {
        $shop = \App\Models\Shop::create([
            'name' => 'Test Shop',
            'slug' => 'test-shop',
            'description' => 'Test Description',
            'is_active' => true,
        ]);
    }
    
    // Créer une commande de test
    $order = \App\Models\Order::create([
        'shop_id' => $shop->id,
        'user_id' => $user->id,
        'order_number' => 'TEST-' . time(),
        'customer_name' => 'Test Customer',
        'customer_email' => 'customer@test.com',
        'items' => [['product_name' => 'Test Product', 'quantity' => 1, 'price' => 10.00]],
        'total_amount' => 10.00,
        'status' => 'pending'
    ]);
    
    return "Test setup complete! User: {$user->email}, Shop: {$shop->name}, Order: {$order->order_number}";
});

// Route de test pour l'upload (sans authentification)
Route::get('/test-upload', function () {
    $shop = \App\Models\Shop::first();
    if (!$shop) {
        return "Aucune boutique trouvée. Allez d'abord sur /test-payment-proof";
    }
    
    $order = \App\Models\Order::where('shop_id', $shop->id)
                             ->whereNull('payment_proof')
                             ->latest()
                             ->first();
    
    if (!$order) {
        return "Aucune commande en attente. Allez d'abord sur /test-payment-proof";
    }
    
    return view('shop.upload-payment-proof', compact('order', 'shop'));
});

// Route de test pour simuler l'authentification
Route::get('/test-upload-auth', function () {
    // Simuler l'authentification
    $user = \App\Models\User::first();
    if (!$user) {
        return "Aucun utilisateur trouvé. Allez d'abord sur /test-payment-proof";
    }
    
    // Se connecter automatiquement
    auth()->login($user);
    
    $shop = \App\Models\Shop::first();
    $order = \App\Models\Order::where('shop_id', $shop->id)
                             ->where('user_id', $user->id)
                             ->whereNull('payment_proof')
                             ->latest()
                             ->first();
    
    if (!$order) {
        return "Aucune commande en attente pour cet utilisateur. Allez d'abord sur /test-payment-proof";
    }
    
    return view('shop.upload-payment-proof', compact('order', 'shop'));
});

// Route publique temporaire pour tester l'upload (À SUPPRIMER EN PRODUCTION)
Route::get('/upload-payment-proof-public', function (Request $request) {
    // Utiliser l'utilisateur connecté s'il existe, sinon le premier utilisateur
    $user = auth()->user() ?? \App\Models\User::first();
    
    // Récupérer la boutique courante depuis la session ou l'URL
    $shop = null;
    
    // Essayer de récupérer la boutique depuis la session
    if (session('current_shop_id')) {
        $shop = \App\Models\Shop::find(session('current_shop_id'));
    }
    
    // Si pas de boutique en session, utiliser la première
    if (!$shop) {
        $shop = \App\Models\Shop::first();
    }
    
    if (!$user || !$shop) {
        return "Données manquantes. Allez d'abord sur /test-payment-proof";
    }
    
    $order = \App\Models\Order::where('shop_id', $shop->id)
                             ->where('user_id', $user->id)
                             ->whereNull('payment_proof')
                             ->latest()
                             ->first();
    
    if (!$order) {
        // Créer automatiquement une commande de test
        $order = \App\Models\Order::create([
            'shop_id' => $shop->id,
            'user_id' => $user->id,
            'order_number' => 'AUTO-' . time(),
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'items' => [
                [
                    'product_name' => 'Produit Test',
                    'quantity' => 1,
                    'price' => 25.00
                ]
            ],
            'total_amount' => 25.00,
            'status' => 'pending'
        ]);
    }
    
    return view('shop.upload-payment-proof', compact('order', 'shop'));
});

// Route POST pour traiter l'upload de preuve de paiement
Route::post('/upload-payment-proof-public', function (Request $request) {
    $user = auth()->user() ?? \App\Models\User::first();
    
    // Récupérer la boutique courante depuis la session
    $shop = null;
    if (session('current_shop_id')) {
        $shop = \App\Models\Shop::find(session('current_shop_id'));
    }
    
    // Si pas de boutique en session, utiliser la première
    if (!$shop) {
        $shop = \App\Models\Shop::first();
    }
    
    if (!$user || !$shop) {
        return redirect()->back()->with('error', 'Erreur de session');
    }
    
    // Récupérer la commande en attente
    $order = \App\Models\Order::where('shop_id', $shop->id)
                             ->where('user_id', $user->id)
                             ->whereNull('payment_proof')
                             ->latest()
                             ->first();
    
    if (!$order) {
        return redirect()->back()->with('error', 'Aucune commande en attente');
    }
    
    // Validation du fichier
    $request->validate([
        'payment_proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120', // 5MB max
    ]);
    
    try {
        // Créer le dossier s'il n'existe pas
        $directory = "shops/{$shop->slug}/payment_proofs";
        if (!\Storage::disk('public')->exists($directory)) {
            \Storage::disk('public')->makeDirectory($directory);
        }
        
        // Sauvegarder le fichier
        $filePath = $request->file('payment_proof')->store(
            $directory, 
            'public'
        );
        
        // Mettre à jour la commande
        $order->update([
            'payment_proof' => $filePath,
            'status' => 'confirmed' // Changer le statut en "confirmé"
        ]);
        
        // Nettoyer le panier après l'upload de la preuve
        session()->forget('cart');
        session()->forget('cart_count');
        
        return redirect("/payment-success/{$shop->slug}")->with('success', 'Preuve de paiement envoyée avec succès ! Votre commande est en cours de traitement.');
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Erreur lors de l\'upload du fichier: ' . $e->getMessage());
    }
});

// Route pour la page de succès après upload
Route::get('/payment-success', function () {
    $shop = null;
    if (session('current_shop_id')) {
        $shop = \App\Models\Shop::find(session('current_shop_id'));
    }
    if (!$shop) {
        $shop = \App\Models\Shop::first();
    }
    return view('shop.payment-success', compact('shop'));
});

// Route pour la page de succès avec slug de boutique
Route::get('/payment-success/{shop_slug}', function ($shop_slug) {
    $shop = \App\Models\Shop::where('slug', $shop_slug)->first();
    
    if (!$shop) {
        abort(404, 'Boutique non trouvée');
    }
    
    return view('shop.payment-success', compact('shop'));
});

// Route de diagnostic pour voir les commandes
Route::get('/debug-orders', function () {
    $user = auth()->user() ?? \App\Models\User::first();
    $shop = \App\Models\Shop::first();
    
    if (!$user || !$shop) {
        return "Données manquantes";
    }
    
    $allOrders = \App\Models\Order::where('shop_id', $shop->id)
                                 ->where('user_id', $user->id)
                                 ->get();
    
    $pendingOrder = \App\Models\Order::where('shop_id', $shop->id)
                                    ->where('user_id', $user->id)
                                    ->whereNull('payment_proof')
                                    ->latest()
                                    ->first();
    
    $latestOrder = \App\Models\Order::where('shop_id', $shop->id)
                                   ->where('user_id', $user->id)
                                   ->latest()
                                   ->first();
    
    return "
    <h3>Debug des commandes</h3>
    <p><strong>Utilisateur connecté:</strong> " . ($user ? $user->email : 'Aucun') . "</p>
    <p><strong>Boutique:</strong> " . ($shop ? $shop->name : 'Aucune') . "</p>
    <p><strong>Total commandes:</strong> " . $allOrders->count() . "</p>
    <p><strong>Commande en attente (sans preuve):</strong> " . ($pendingOrder ? $pendingOrder->order_number : 'Aucune') . "</p>
    <p><strong>Dernière commande:</strong> " . ($latestOrder ? $latestOrder->order_number . ' - Preuve: ' . ($latestOrder->payment_proof ? 'Oui' : 'Non') : 'Aucune') . "</p>
    <p><strong>Toutes les commandes:</strong></p>
    <ul>
    " . $allOrders->map(function($order) {
        return "<li>{$order->order_number} - Statut: {$order->status} - Preuve: " . ($order->payment_proof ? 'Oui' : 'Non') . " - Date: " . $order->created_at->format('d/m/Y H:i') . "</li>";
    })->join('') . "
    </ul>
    ";
});

// Route pour créer une commande de test pour l'utilisateur connecté
Route::get('/create-test-order', function () {
    $user = auth()->user();
    $shop = \App\Models\Shop::first();
    
    if (!$user || !$shop) {
        return "Vous devez être connecté et avoir une boutique";
    }
    
    // Créer une commande de test pour l'utilisateur connecté
    $order = \App\Models\Order::create([
        'shop_id' => $shop->id,
        'user_id' => $user->id,
        'order_number' => 'TEST-' . time(),
        'customer_name' => $user->name,
        'customer_email' => $user->email,
        'items' => [
            [
                'product_name' => 'Produit Test',
                'quantity' => 1,
                'price' => 25.00
            ]
        ],
        'total_amount' => 25.00,
        'status' => 'pending'
    ]);
    
    return "Commande de test créée : {$order->order_number} pour {$user->email}";
});

// Route de test pour diagnostiquer l'upload
Route::get('/test-upload-debug', function () {
    $shop = \App\Models\Shop::first();
    $directory = "shops/{$shop->slug}/payment_proofs";
    
    $info = [
        'Shop' => $shop ? $shop->name : 'Aucune',
        'Directory' => $directory,
        'Storage exists' => \Storage::disk('public')->exists($directory),
        'Storage path' => storage_path('app/public'),
        'Public path' => public_path('storage'),
        'Is writable' => is_writable(storage_path('app/public')),
    ];
    
    return "<pre>" . print_r($info, true) . "</pre>";
});

// Route de test simple pour l'upload
Route::post('/test-upload-simple', function (Request $request) {
    try {
        $shop = \App\Models\Shop::first();
        $directory = "shops/{$shop->slug}/payment_proofs";
        
        // Créer le dossier s'il n'existe pas
        if (!\Storage::disk('public')->exists($directory)) {
            \Storage::disk('public')->makeDirectory($directory);
        }
        
        // Sauvegarder le fichier
        $filePath = $request->file('payment_proof')->store(
            $directory, 
            'public'
        );
        
        return "Fichier uploadé avec succès : {$filePath}";
        
    } catch (\Exception $e) {
        return "Erreur : " . $e->getMessage();
    }
});

// Route pour afficher la page de test d'upload
Route::get('/test-upload-page', function () {
    return view('test-upload');
});

// Route pour forcer l'affichage de la page d'upload (même sans commande)
Route::get('/show-upload-page', function (Request $request) {
    $user = auth()->user() ?? \App\Models\User::first();
    
    // Récupérer la boutique courante depuis la session
    $shop = null;
    if (session('current_shop_id')) {
        $shop = \App\Models\Shop::find(session('current_shop_id'));
    }
    if (!$shop) {
        $shop = \App\Models\Shop::first();
    }
    
    if (!$user || !$shop) {
        return "Données manquantes";
    }
    
    // Créer une commande de test si elle n'existe pas
    $order = \App\Models\Order::where('shop_id', $shop->id)
                             ->where('user_id', $user->id)
                             ->whereNull('payment_proof')
                             ->latest()
                             ->first();
    
    if (!$order) {
        $order = \App\Models\Order::create([
            'shop_id' => $shop->id,
            'user_id' => $user->id,
            'order_number' => 'AUTO-' . time(),
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'items' => [
                [
                    'product_name' => 'Produit Test',
                    'quantity' => 1,
                    'price' => 25.00
                ]
            ],
            'total_amount' => 25.00,
            'status' => 'pending'
        ]);
    }
    
    return view('shop.upload-payment-proof', compact('order', 'shop'));
});

// Route avec spécification de boutique dans l'URL
Route::get('/upload-payment-proof/{shop_slug}', function ($shop_slug) {
    $user = auth()->user() ?? \App\Models\User::first();
    $shop = \App\Models\Shop::where('slug', $shop_slug)->first();
    
    if (!$user || !$shop) {
        return "Boutique ou utilisateur introuvable";
    }
    
    // Créer une commande de test si elle n'existe pas
    $order = \App\Models\Order::where('shop_id', $shop->id)
                             ->where('user_id', $user->id)
                             ->whereNull('payment_proof')
                             ->latest()
                             ->first();
    
    if (!$order) {
        $order = \App\Models\Order::create([
            'shop_id' => $shop->id,
            'user_id' => $user->id,
            'order_number' => 'AUTO-' . time(),
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'items' => [
                [
                    'product_name' => 'Produit Test',
                    'quantity' => 1,
                    'price' => 25.00
                ]
            ],
            'total_amount' => 25.00,
            'status' => 'pending'
        ]);
    }
    
    return view('shop.upload-payment-proof', compact('order', 'shop'));
});

// Route POST avec spécification de boutique dans l'URL
Route::post('/upload-payment-proof/{shop_slug}', function (Request $request, $shop_slug) {
    $user = auth()->user() ?? \App\Models\User::first();
    $shop = \App\Models\Shop::where('slug', $shop_slug)->first();
    
    if (!$user || !$shop) {
        return redirect()->back()->with('error', 'Boutique ou utilisateur introuvable');
    }
    
    // Récupérer la commande la plus récente de l'utilisateur pour cette boutique
    $order = \App\Models\Order::where('shop_id', $shop->id)
                             ->where('user_id', $user->id)
                             ->latest()
                             ->first();
    
    if (!$order) {
        return redirect()->back()->with('error', 'Aucune commande trouvée pour cette boutique');
    }
    
    // Si la commande a déjà une preuve de paiement, créer une nouvelle commande de test
    if ($order->payment_proof) {
        $order = \App\Models\Order::create([
            'shop_id' => $shop->id,
            'user_id' => $user->id,
            'order_number' => 'AUTO-' . time(),
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'items' => [
                [
                    'product_name' => 'Produit Test',
                    'quantity' => 1,
                    'price' => 25.00
                ]
            ],
            'total_amount' => 25.00,
            'status' => 'pending'
        ]);
    }
    
    // Validation du fichier
    $request->validate([
        'payment_proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120', // 5MB max
    ]);
    
    try {
        // Créer le dossier s'il n'existe pas
        $directory = "shops/{$shop->slug}/payment_proofs";
        if (!\Storage::disk('public')->exists($directory)) {
            \Storage::disk('public')->makeDirectory($directory);
        }
        
        // Sauvegarder le fichier
        $filePath = $request->file('payment_proof')->store(
            $directory, 
            'public'
        );
        
        // Mettre à jour la commande
        $order->update([
            'payment_proof' => $filePath,
            'status' => 'confirmed' // Changer le statut en "confirmé"
        ]);
        
        // Nettoyer le panier après l'upload de la preuve
        session()->forget('cart');
        session()->forget('cart_count');
        
        return redirect("/payment-success/{$shop->slug}")->with('success', 'Preuve de paiement envoyée avec succès ! Votre commande est en cours de traitement.');
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Erreur lors de l\'upload du fichier: ' . $e->getMessage());
    }
});

// ========================================
// ROUTES UTILITAIRES
// ========================================

// Route pour vider le panier
Route::post('/clear-cart', [App\Http\Controllers\Auth\LoginController::class, 'clearCart'])->name('clear-cart');

// Route pour nettoyer le panier après achat
Route::post('/clear-cart-after-purchase', function () {
    session()->forget('cart');
    session()->forget('cart_count');
    return response()->json(['success' => true]);
});

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