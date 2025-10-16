<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Afficher la liste des produits d'une boutique
     */
    public function index(Shop $shop)
    {
        $products = $shop->products()->with('category')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.products.index', compact('shop', 'products'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Shop $shop)
    {
        $categories = $shop->categories()->where('is_active', true)->get();
        return view('admin.products.create', compact('shop', 'categories'));
    }

    /**
     * Enregistrer un nouveau produit
     */
    public function store(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sizes' => 'nullable|array',
            'sizes.*' => 'nullable|string',
            'colors' => 'nullable|array',
            'colors.*' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean'
        ]);

        // Upload de l'image principale
        $imagePath = $request->file('image')->store(
            "shops/{$shop->slug}/products", 
            'public'
        );
        $validated['image'] = $imagePath;

        // Upload des images supplémentaires
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store(
                    "shops/{$shop->slug}/products", 
                    'public'
                );
            }
        }
        $validated['images'] = $images;

        // Nettoyer les arrays de tailles et couleurs (supprimer les valeurs vides)
        if (isset($validated['sizes'])) {
            $validated['sizes'] = array_filter($validated['sizes'], function($size) {
                return !empty(trim($size));
            });
        }
        
        if (isset($validated['colors'])) {
            $validated['colors'] = array_filter($validated['colors'], function($color) {
                return !empty(trim($color));
            });
        }

        // Ajouter le shop_id
        $validated['shop_id'] = $shop->id;

        $shop->products()->create($validated);

        return redirect()->route('admin.shops.products.index', $shop)
            ->with('success', 'Produit ajouté avec succès !');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Shop $shop, Product $product)
    {
        $categories = $shop->categories()->where('is_active', true)->get();
        return view('admin.products.edit', compact('shop', 'product', 'categories'));
    }

    /**
     * Mettre à jour un produit
     */
    public function update(Request $request, Shop $shop, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sizes' => 'nullable|array',
            'sizes.*' => 'nullable|string',
            'colors' => 'nullable|array',
            'colors.*' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean'
        ]);

        // Upload de la nouvelle image principale si fournie
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store(
                "shops/{$shop->slug}/products", 
                'public'
            );
            $validated['image'] = $imagePath;
        }

        // Upload des nouvelles images supplémentaires si fournies
        if ($request->hasFile('images')) {
            // Supprimer les anciennes images
            if ($product->images) {
                foreach ($product->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store(
                    "shops/{$shop->slug}/products", 
                    'public'
                );
            }
            $validated['images'] = $images;
        }

        // Nettoyer les arrays de tailles et couleurs (supprimer les valeurs vides)
        if (isset($validated['sizes'])) {
            $validated['sizes'] = array_filter($validated['sizes'], function($size) {
                return !empty(trim($size));
            });
        }
        
        if (isset($validated['colors'])) {
            $validated['colors'] = array_filter($validated['colors'], function($color) {
                return !empty(trim($color));
            });
        }

        $product->update($validated);

        return redirect()->route('admin.shops.products.index', $shop)
            ->with('success', 'Produit mis à jour avec succès !');
    }

    /**
     * Supprimer un produit
     */
    public function destroy(Shop $shop, Product $product)
    {
        // Supprimer l'image principale
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Supprimer les images supplémentaires
        if ($product->images) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return redirect()->route('admin.shops.products.index', $shop)
            ->with('success', 'Produit supprimé avec succès !');
    }
}
