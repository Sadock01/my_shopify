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

        // Filtrage par catégorie
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
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
        $categories = $shop->products()->with('category')->get()->pluck('category')->unique();

        return view('shop.products', compact('shop', 'products', 'categories'));
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
        // Si le shop n'est pas injecté (routes avec domaine), le récupérer depuis les attributs
        if (!$shop) {
            $shop = $request->attributes->get('current_shop');
            
            if (!$shop) {
                abort(404);
            }
        }

        // Récupérer le panier depuis la session si l'utilisateur est connecté
        $cart = [];
        if (Auth::check()) {
            $cart = session()->get('cart_' . $shop->id, []);
        }

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
        
        if (empty($cart)) {
            return redirect()->route('shop.cart')->with('error', 'Votre panier est vide.');
        }

        return view('shop.payment-info', compact('shop', 'cart'));
    }

    /**
     * Traitement de la commande (nécessite authentification)
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

        // Récupérer le panier depuis la session
        $cart = session()->get('cart_' . $shop->id, []);
        
        if (empty($cart)) {
            return redirect()->route('shop.cart')->with('error', 'Votre panier est vide.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'nullable|string',
            'customer_address' => 'nullable|string',
            'items' => 'required|array',
            'total_amount' => 'required|numeric|min:0',
        ]);

        // Créer la commande avec l'ID de l'utilisateur connecté
        $order = $shop->orders()->create([
            'order_number' => \App\Models\Order::generateOrderNumber(),
            'user_id' => Auth::id(), // Ajouter l'ID de l'utilisateur connecté
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'customer_address' => $validated['customer_address'],
            'items' => $validated['items'],
            'total_amount' => $validated['total_amount'],
            'status' => 'pending'
        ]);

        // Vider le panier
        session()->forget('cart_' . $shop->id);

        return redirect()->route('shop.payment-info')->with('success', 'Commande créée avec succès !');
    }
}
