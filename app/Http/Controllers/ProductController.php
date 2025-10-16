<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $products = Product::with(['shop', 'category'])->active()->get();
        
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'image' => 'required|string',
            'images' => 'nullable|array',
            'sizes' => 'nullable|array',
            'sizes.*' => 'nullable|string',
            'colors' => 'nullable|array',
            'colors.*' => 'nullable|string',
            'is_featured' => 'boolean',
            'stock' => 'integer|min:0',
        ]);

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

        $product = Product::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Produit créé avec succès',
            'data' => $product->load(['shop', 'category'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $product->load(['shop', 'category'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'shop_id' => 'sometimes|exists:shops,id',
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'image' => 'sometimes|string',
            'images' => 'nullable|array',
            'sizes' => 'nullable|array',
            'sizes.*' => 'nullable|string',
            'colors' => 'nullable|array',
            'colors.*' => 'nullable|string',
            'is_featured' => 'boolean',
            'stock' => 'integer|min:0',
        ]);

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

        return response()->json([
            'success' => true,
            'message' => 'Produit mis à jour avec succès',
            'data' => $product->load(['shop', 'category'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produit supprimé avec succès'
        ]);
    }

    /**
     * Get products for a specific shop
     */
    public function getShopProducts(Shop $shop): JsonResponse
    {
        $products = $shop->products()->with('category')->active()->get();
        
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get products by category for a specific shop
     */
    public function getProductsByCategory(Shop $shop, Category $category): JsonResponse
    {
        $products = $shop->products()
            ->where('category_id', $category->id)
            ->with('category')
            ->active()
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
}
