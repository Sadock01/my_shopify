<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

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
     * Détails d'un produit
     */
    public function product(Request $request, $productId, Shop $shop = null)
    {
        // Si le shop n'est pas injecté (routes avec domaine), le récupérer depuis les attributs
        if (!$shop) {
            $shop = $request->attributes->get('current_shop');
            
            if (!$shop) {
                abort(404);
            }
        }

        $product = $shop->products()
            ->where('id', $productId)
            ->with('category')
            ->active()
            ->first();

        if (!$product) {
            abort(404);
        }

        // Produits similaires
        $relatedProducts = $shop->products()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->take(4)
            ->get();

        return view('shop.product', compact('shop', 'product', 'relatedProducts'));
    }

    /**
     * Produits par catégorie
     */
    public function category(Request $request, $categorySlug, Shop $shop = null)
    {
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

        // Récupérer le panier depuis la session
        $cart = session()->get('cart_' . $shop->id, []);

        return view('shop.cart', compact('shop', 'cart'));
    }

    /**
     * Page de paiement
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

        return view('shop.payment-info', compact('shop'));
    }

    /**
     * Traitement de la commande
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

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'nullable|string',
            'customer_address' => 'nullable|string',
            'items' => 'required|array',
            'total_amount' => 'required|numeric|min:0',
        ]);

        // Créer la commande
        $order = $shop->orders()->create([
            'order_number' => \App\Models\Order::generateOrderNumber(),
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
