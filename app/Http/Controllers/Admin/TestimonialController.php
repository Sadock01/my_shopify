<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Afficher la liste des témoignages d'une boutique
     */
    public function index(Shop $shop)
    {
        $testimonials = $shop->testimonials()->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.testimonials.index', compact('shop', 'testimonials'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Shop $shop)
    {
        return view('admin.testimonials.create', compact('shop'));
    }

    /**
     * Enregistrer un nouveau témoignage
     */
    public function store(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_location' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10',
            'is_featured' => 'boolean'
        ]);

        // Ajouter le shop_id
        $validated['shop_id'] = $shop->id;

        $shop->testimonials()->create($validated);

        return redirect()->route('admin.shops.testimonials.index', $shop)
            ->with('success', 'Témoignage ajouté avec succès !');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Shop $shop, Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('shop', 'testimonial'));
    }

    /**
     * Mettre à jour un témoignage
     */
    public function update(Request $request, Shop $shop, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_location' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10',
            'is_featured' => 'boolean'
        ]);

        $testimonial->update($validated);

        return redirect()->route('admin.shops.testimonials.index', $shop)
            ->with('success', 'Témoignage mis à jour avec succès !');
    }

    /**
     * Supprimer un témoignage
     */
    public function destroy(Shop $shop, Testimonial $testimonial)
    {
        $testimonial->delete();

        return redirect()->route('admin.shops.testimonials.index', $shop)
            ->with('success', 'Témoignage supprimé avec succès !');
    }
}
