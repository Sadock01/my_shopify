<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Afficher la liste des catégories d'une boutique
     */
    public function index(Shop $shop)
    {
        $categories = $shop->categories()->withCount('products')->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('shop', 'categories'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Shop $shop)
    {
        return view('admin.categories.create', compact('shop'));
    }

    /**
     * Enregistrer une nouvelle catégorie
     */
    public function store(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        // Générer le slug automatiquement et s'assurer qu'il est unique pour cette boutique
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        
        // Vérifier l'unicité du slug pour cette boutique
        while ($shop->categories()->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $validated['slug'] = $slug;
        
        // Upload de l'image si fournie
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store(
                "shops/{$shop->slug}/categories", 
                'public'
            );
            $validated['image'] = $imagePath;
        }

        // Définir l'ordre si non fourni
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = $shop->categories()->max('sort_order') + 1;
        }

        // Ajouter le shop_id
        $validated['shop_id'] = $shop->id;

        $shop->categories()->create($validated);

        return redirect()->route('admin.shops.categories.index', $shop)
            ->with('success', 'Catégorie ajoutée avec succès !');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Shop $shop, Category $category)
    {
        return view('admin.categories.edit', compact('shop', 'category'));
    }

    /**
     * Mettre à jour une catégorie
     */
    public function update(Request $request, Shop $shop, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        // Générer le slug automatiquement et s'assurer qu'il est unique pour cette boutique
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        
        // Vérifier l'unicité du slug pour cette boutique (en excluant la catégorie actuelle)
        while ($shop->categories()->where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $validated['slug'] = $slug;

        // Upload de la nouvelle image si fournie
        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $imagePath = $request->file('image')->store(
                "shops/{$shop->slug}/categories", 
                'public'
            );
            $validated['image'] = $imagePath;
        }

        $category->update($validated);

        return redirect()->route('admin.shops.categories.index', $shop)
            ->with('success', 'Catégorie mise à jour avec succès !');
    }

    /**
     * Supprimer une catégorie
     */
    public function destroy(Shop $shop, Category $category)
    {
        // Vérifier qu'il n'y a pas de produits dans cette catégorie
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.shops.categories.index', $shop)
                ->with('error', 'Impossible de supprimer une catégorie qui contient des produits.');
        }

        // Supprimer l'image si elle existe
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.shops.categories.index', $shop)
            ->with('success', 'Catégorie supprimée avec succès !');
    }
}
