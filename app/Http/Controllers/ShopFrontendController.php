<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopFrontendController extends Controller
{
    /**
     * Page d'accueil de la boutique
     */
    public function home(Request $request, Shop $shop = null)
    {
        // Si le shop n'est pas injecté (routes avec domaine), le récupérer depuis les attributs
        if (!$shop) {
            $shop = $request->attributes->get('current_shop');
            
            if (!$shop) {
                abort(404);
            }
        }

        // Produits vedettes
        $featuredProducts = $shop->products()
            ->featured()
            ->active()
            ->with('category')
            ->take(8)
            ->get();

        // Nouvelles arrivées
        $newProducts = $shop->products()
            ->active()
            ->with('category')
            ->latest()
            ->take(4)
            ->get();

        // Charger les témoignages et moyens de paiement
        $shop->load(['testimonials', 'paymentMethods']);

        return view('shop.home', compact('shop', 'featuredProducts', 'newProducts'));
    }

    /**
     * Liste des produits
     */
    public function products(Request $request, Shop $shop = null)
    {
        // Si le shop n'est pas injecté (routes avec domaine), le récupérer depuis les attributs
        if (!$shop) {
            $shop = $request->attributes->get('current_shop');
            
            if (!$shop) {
                abort(404);
            }
        }

        $query = $shop->products()->with('category')->active();

        // Recherche par nom ou description
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filtrage par catégorie
        if ($request->has('category') && !empty($request->category)) {
            $query->whereIn('category_id', (array) $request->category);
        }

        // Filtrage par prix minimum
        if ($request->has('min_price') && !empty($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }

        // Filtrage par prix maximum
        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filtrage par disponibilité
        if ($request->has('availability')) {
            if ($request->availability === 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($request->availability === 'out_of_stock') {
                $query->where('stock', 0);
            }
        }

        // Tri
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12);
        $categories = $shop->categories()->where('is_active', true)->get();

        // Calculer les prix min/max pour les filtres
        $priceRange = $shop->products()->active()->selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();
        $minPrice = $priceRange->min_price ?? 0;
        $maxPrice = $priceRange->max_price ?? 1000;

        // Si c'est une requête AJAX, retourner JSON
        if ($request->ajax()) {
            return response()->json([
                'products' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ],
                'filters' => [
                    'min_price' => $minPrice,
                    'max_price' => $maxPrice,
                    'search' => $request->search,
                    'category' => $request->category,
                    'sort' => $request->sort,
                ]
            ]);
        }

        return view('shop.products', compact('shop', 'products', 'categories', 'minPrice', 'maxPrice'));
    }

    /**
     * Page produit individuel
     */
    public function product(Request $request, $shop, $productId)
    {
        // Si le shop est une chaîne (slug), le convertir en objet Shop
        if (is_string($shop)) {
            $shop = Shop::where('slug', $shop)->firstOrFail();
        }
        
        // Si le shop n'est pas injecté (routes avec domaine), le récupérer depuis les attributs
        if (!$shop) {
            $shop = $request->attributes->get('current_shop');
            
            if (!$shop) {
                abort(404);
            }
        }

        $product = $shop->products()
            ->with('category')
            ->active()
            ->findOrFail($productId);

        // Produits similaires (même catégorie)
        $relatedProducts = $shop->products()
            ->with('category')
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('shop.product', compact('shop', 'product', 'relatedProducts'));
    }

    /**
     * Produits par catégorie
     */
    public function category(Request $request, $shop, $categorySlug)
    {
        // Si le shop est une chaîne (slug), le convertir en objet Shop
        if (is_string($shop)) {
            $shop = Shop::where('slug', $shop)->firstOrFail();
        }
        
        // Si le shop n'est pas injecté (routes avec domaine), le récupérer depuis les attributs
        if (!$shop) {
            $shop = $request->attributes->get('current_shop');
            
            if (!$shop) {
                abort(404);
            }
        }

        $category = Category::where('slug', $categorySlug)->first();
        
        if (!$category) {
            abort(404);
        }

        $products = $shop->products()
            ->where('category_id', $category->id)
            ->with('category')
            ->active()
            ->paginate(12);

        return view('shop.category', compact('shop', 'category', 'products'));
    }

    /**
     * Panier
     */
    public function cart(Request $request, Shop $shop = null)
    {
        \Log::info('=== MÉTHODE CART APPELÉE ===', [
            'url' => $request->url(),
            'method' => $request->method(),
            'is_ajax' => $request->ajax(),
            'has_cart_data' => $request->has('cart_data'),
            'user' => auth()->user() ? auth()->user()->email : 'non connecté'
        ]);
        
        // Si le shop n'est pas injecté (routes avec domaine), le récupérer depuis les attributs
        if (!$shop) {
            $shop = $request->attributes->get('current_shop');
            
            if (!$shop) {
                abort(404);
            }
        }
        
        \Log::info('Shop détecté', [
            'shop_id' => $shop->id,
            'shop_slug' => $shop->slug,
            'shop_name' => $shop->name
        ]);

        // Si c'est une requête AJAX pour récupérer le panier localStorage
        if ($request->ajax() && $request->has('cart_data')) {
            $cart = json_decode($request->input('cart_data'), true) ?? [];
            \Log::info('=== REQUÊTE AJAX REÇUE ===', [
                'cart_data_raw' => $request->input('cart_data'),
                'cart_parsed' => $cart,
                'cart_count' => count($cart)
            ]);
            
            // Synchroniser vers la session
            session(['cart_' . $shop->id => $cart]);
            
            \Log::info('Panier sauvegardé en session', [
                'session_key' => 'cart_' . $shop->id,
                'session_cart' => session()->get('cart_' . $shop->id, [])
            ]);
            
            return response()->json(['success' => true, 'cart' => $cart]);
        }

        // Récupérer le panier depuis la session si l'utilisateur est connecté
        $cart = [];
        if (Auth::check()) {
            $cart = session()->get('cart_' . $shop->id, []);
            
            \Log::info('Panier récupéré depuis session', [
                'session_key' => 'cart_' . $shop->id,
                'cart_content' => $cart,
                'cart_count' => count($cart)
            ]);
            
            // Si le panier de session est vide, essayer de récupérer depuis localStorage via AJAX
            if (empty($cart)) {
                \Log::info('Panier session vide dans cart, vérification localStorage nécessaire');
            }
        } else {
            \Log::info('Utilisateur non connecté');
        }

        \Log::info('Retour de la vue cart', [
            'cart_count' => count($cart),
            'cart_content' => $cart
        ]);

        return view('shop.cart', compact('shop', 'cart'));
    }

    /**
     * Obtenir le nombre d'articles dans le panier
     */
    public function getCartCount(Request $request, Shop $shop = null)
    {
        // Si le shop n'est pas injecté (routes avec domaine), le récupérer depuis les attributs
        if (!$shop) {
            $shop = $request->attributes->get('current_shop');
            
            if (!$shop) {
                return response()->json(['count' => 0]);
            }
        }

        $count = 0;
        if (Auth::check()) {
            $cart = session()->get('cart_' . $shop->id, []);
            $count = count($cart);
        }

        return response()->json(['count' => $count]);
    }

    /**
     * Synchroniser le panier localStorage vers la session
     */
    public function syncCart(Request $request, Shop $shop = null)
    {
        // Si le shop n'est pas injecté (routes avec domaine), le récupérer depuis les attributs
        if (!$shop) {
            $shop = $request->attributes->get('current_shop');
            
            if (!$shop) {
                return response()->json(['success' => false, 'message' => 'Boutique non trouvée']);
            }
        }

        // Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Utilisateur non connecté']);
        }

        // Récupérer le panier depuis la requête
        $cartData = $request->input('cart', []);
        
        // Sauvegarder dans la session
        session(['cart_' . $shop->id => $cartData]);

        return response()->json(['success' => true, 'message' => 'Panier synchronisé']);
    }

    /**
     * Récupérer les informations des produits du panier
     */
    public function getCartProducts(Request $request, Shop $shop = null)
    {
        // Si le shop n'est pas injecté (routes avec domaine), le récupérer depuis les attributs
        if (!$shop) {
            $shop = $request->attributes->get('current_shop');
            
            if (!$shop) {
                return response()->json(['success' => false, 'message' => 'Boutique non trouvée']);
            }
        }

        // Récupérer les IDs des produits depuis la requête
        $productIds = $request->input('product_ids', []);
        
        if (empty($productIds)) {
            return response()->json(['success' => true, 'products' => []]);
        }

        // Récupérer les produits depuis la base de données
        $products = $shop->products()
            ->whereIn('id', $productIds)
            ->with('category')
            ->active()
            ->get()
            ->keyBy('id');

        // Ajouter un produit fictif pour les tests (ID 999)
        if (in_array(999, $productIds)) {
            $products[999] = (object) [
                'id' => 999,
                'name' => 'Produit Test',
                'price' => 29.99,
                'image' => 'https://via.placeholder.com/200x200?text=Test',
                'category' => (object) ['name' => 'Test']
            ];
        }

        return response()->json(['success' => true, 'products' => $products]);
    }

    /**
     * Page de paiement (nécessite authentification)
     */
    public function paymentInfo(Request $request, Shop $shop = null)
    {
        // Si le shop n'est pas injecté (routes avec domaine), le récupérer depuis les attributs
        if (!$shop) {
            $shop = $request->attributes->get('current_shop');
            
            if (!$shop) {
                abort(404);
            }
        }

        // Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Récupérer le panier depuis la session
        $cart = session()->get('cart_' . $shop->id, []);
        
        // Si le panier est vide, continuer quand même (peut venir d'une commande récente)
        if (empty($cart)) {
            $cart = [];
            \Log::info('Panier session vide dans paymentInfo, continuation avec panier vide');
        }

        return view('shop.payment-info', compact('shop', 'cart'));
    }

    /**
     * Page des informations de livraison
     */
    public function deliveryInfo(Request $request, Shop $shop = null)
    {
        // Debug temporaire
        \Log::info('deliveryInfo appelé', [
            'shop' => $shop ? $shop->slug : 'null',
            'url' => $request->url(),
            'method' => $request->method(),
            'user' => auth()->user() ? auth()->user()->email : 'non connecté'
        ]);
        
        // Si le shop n'est pas injecté (routes avec domaine), le récupérer depuis les attributs
        if (!$shop) {
            $shop = $request->attributes->get('current_shop');
            
            if (!$shop) {
                abort(404);
            }
        }

        // Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour passer une commande.');
        }

        // Récupérer le panier depuis la session
        $cart = session()->get('cart_' . $shop->id, []);
        
        \Log::info('Panier récupéré dans deliveryInfo', [
            'shop_id' => $shop->id,
            'cart_key' => 'cart_' . $shop->id,
            'cart_content' => $cart,
            'cart_count' => count($cart)
        ]);
        
        // Si le panier est vide, continuer quand même (les données viendront du formulaire)
        if (empty($cart)) {
            $cart = [];
            \Log::info('Panier session vide dans deliveryInfo, continuation avec panier vide');
        }

        // Enrichir le panier pour l'affichage (name, price, image)
        $cartDisplay = [];
        if (!empty($cart)) {
            $productIds = array_column($cart, 'product_id');
            $products = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');
            
            foreach ($cart as $item) {
                if (isset($products[$item['product_id']])) {
                    $product = $products[$item['product_id']];
                    $cartDisplay[] = [
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'price' => (float) $product->price,
                        'quantity' => (int) ($item['quantity'] ?? 1),
                        'image' => $product->image,
                    ];
                }
            }
        }
        
        \Log::info('Panier enrichi pour deliveryInfo', [
            'cart_display' => $cartDisplay,
            'cart_count' => count($cartDisplay)
        ]);

        return view('shop.checkout-delivery', ['shop' => $shop, 'cart' => $cartDisplay]);
    }

    /**
     * Traitement des informations de livraison et création de la commande
     */
    public function processCheckout(Request $request, Shop $shop = null)
    {
        // Debug temporaire
        \Log::info('processCheckout appelé', [
            'shop' => $shop ? $shop->slug : 'null',
            'url' => $request->url(),
            'method' => $request->method(),
            'has_cart_data' => $request->has('cart_data'),
            'session_cart' => session()->get('cart_' . ($shop ? $shop->id : 'unknown'), []),
            'csrf_token' => $request->input('_token'),
            'all_inputs' => $request->all()
        ]);
        
        // Si le shop n'est pas injecté (routes avec domaine), le récupérer depuis les attributs
        if (!$shop) {
            $shop = $request->attributes->get('current_shop');
            
            if (!$shop) {
                abort(404);
            }
        }

        // Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour passer une commande.');
        }

        // Récupérer le panier depuis cart_data (priorité) ou session
        $cart = [];
        if ($request->has('cart_data')) {
            $cart = json_decode($request->input('cart_data'), true) ?? [];
            \Log::info('Panier récupéré depuis cart_data', ['cart' => $cart]);
        } else {
            $cart = session()->get('cart_' . $shop->id, []);
            \Log::info('Panier récupéré depuis session', ['cart' => $cart]);
        }

        // Validation des informations de livraison
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:255',
            'delivery_instructions' => 'nullable|string|max:500'
        ]);

        \Log::info('Validation réussie, création de la commande', [
            'validated' => $validated,
            'cart' => $cart
        ]);

        // Récupérer les prix des produits depuis la base de données
        $productIds = array_column($cart, 'product_id');
        $products = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');
        
        \Log::info('Produits récupérés', [
            'product_ids' => $productIds,
            'products' => $products->toArray()
        ]);

        // Calculer le total en utilisant les prix de la base de données
        $totalAmount = 0;
        foreach ($cart as $item) {
            if (isset($products[$item['product_id']])) {
                $product = $products[$item['product_id']];
                $itemTotal = $product->price * $item['quantity'];
                $totalAmount += $itemTotal;
                
                \Log::info('Calcul item', [
                    'product_id' => $item['product_id'],
                    'product_name' => $product->name,
                    'product_price' => $product->price,
                    'quantity' => $item['quantity'],
                    'item_total' => $itemTotal
                ]);
            }
        }

        \Log::info('Calcul du total', [
            'total_amount' => $totalAmount,
            'cart_items' => $cart
        ]);

        // Créer la commande
        try {
            // Préparer les items avec les informations complètes des produits
            $orderItems = [];
            foreach ($cart as $item) {
                if (isset($products[$item['product_id']])) {
                    $product = $products[$item['product_id']];
                    $orderItems[] = [
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $item['quantity'],
                        'image' => $product->image,
                        'category' => $product->category ? $product->category->name : null
                    ];
                }
            }
            
            \Log::info('Items de commande préparés', [
                'order_items' => $orderItems
            ]);
            
            $order = \App\Models\Order::create([
                'shop_id' => $shop->id,
                'user_id' => Auth::id(),
                'order_number' => 'CMD-' . time() . '-' . rand(1000, 9999),
                'customer_name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'customer_email' => $validated['email'],
                'customer_phone' => $validated['phone'],
                'customer_address' => $validated['address'] . ', ' . $validated['city'] . ', ' . $validated['postal_code'] . ', ' . $validated['country'],
                'items' => $orderItems,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'notes' => $validated['delivery_instructions']
            ]);
            
            \Log::info('Commande créée avec succès', [
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de la commande', [
                'error' => $e->getMessage(),
                'cart' => $cart,
                'validated' => $validated
            ]);
            return redirect()->back()->with('error', 'Erreur lors de la création de la commande: ' . $e->getMessage());
        }

        // Vider le panier
        session()->forget('cart_' . $shop->id);

        // Rediriger vers la page de paiement
        $redirectUrl = "/shop/{$shop->slug}/payment-info";
        \Log::info('Redirection vers payment-info', [
            'redirect_url' => $redirectUrl,
            'order_id' => $order->id
        ]);
        
        return redirect()->to($redirectUrl)
            ->with('success', 'Commande créée avec succès ! Veuillez procéder au paiement.');
    }

    /**
     * Traitement de la commande (nécessite authentification) - ANCIENNE MÉTHODE
     */
    public function checkout(Request $request, Shop $shop = null)
    {
        // Si le shop n'est pas injecté (routes avec domaine), le récupérer depuis les attributs
        if (!$shop) {
            $shop = $request->attributes->get('current_shop');
            
            if (!$shop) {
                abort(404);
            }
        }

        // Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour passer une commande.');
        }

        // Récupérer le panier depuis la session ou depuis la requête
        $cart = session()->get('cart_' . $shop->id, []);
        
        // Si le panier de session est vide, vérifier s'il y a des données dans la requête
        if (empty($cart) && $request->has('cart_data')) {
            $cart = json_decode($request->input('cart_data'), true) ?? [];
        }
        
        // Si le panier est toujours vide, rediriger vers le panier
        if (empty($cart)) {
            return redirect()->to("/{$shop->slug}/cart")->with('error', 'Votre panier est vide. Veuillez ajouter des produits avant de continuer.');
        }

        // Validation des informations de livraison
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:255',
        ]);

        // Créer la commande avec l'ID de l'utilisateur connecté
        $order = $shop->orders()->create([
            'order_number' => \App\Models\Order::generateOrderNumber(),
            'user_id' => Auth::id(),
            'customer_name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'customer_email' => $validated['email'],
            'customer_phone' => $validated['phone'],
            'customer_address' => $validated['address'] . ', ' . $validated['city'] . ' ' . $validated['postal_code'] . ', ' . $validated['country'],
            'items' => $cart,
            'total_amount' => array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart)),
            'status' => 'pending'
        ]);

        // Vider le panier
        session()->forget('cart_' . $shop->id);

        // Rediriger vers la page de paiement
        return redirect()->to("/{$shop->slug}/payment-info")->with('success', 'Commande créée avec succès !');
    }
}
