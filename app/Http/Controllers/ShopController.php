<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $shops = Shop::with('template')->active()->get();
        
        return response()->json([
            'success' => true,
            'data' => $shops
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
            'template_id' => 'required|exists:shop_templates,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:shops,slug',
            'domain' => 'nullable|string|unique:shops,domain|regex:/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$/',
            'description' => 'nullable|string',
            'logo' => 'nullable|string',
            'banner_image' => 'nullable|string',
            'theme_settings' => 'required|array',
            'payment_info' => 'required|array',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'about_text' => 'nullable|string',
            'social_links' => 'nullable|array',
        ]);

        $shop = Shop::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Boutique créée avec succès',
            'data' => $shop->load('template')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $shop): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $shop->load('template')
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
    public function update(Request $request, Shop $shop): JsonResponse
    {
        $validated = $request->validate([
            'template_id' => 'sometimes|exists:shop_templates,id',
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:shops,slug,' . $shop->id,
            'domain' => 'nullable|string|unique:shops,domain,' . $shop->id . '|regex:/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$/',
            'description' => 'nullable|string',
            'logo' => 'nullable|string',
            'banner_image' => 'nullable|string',
            'theme_settings' => 'sometimes|array',
            'payment_info' => 'sometimes|array',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'about_text' => 'nullable|string',
            'social_links' => 'nullable|array',
        ]);

        $shop->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Boutique mise à jour avec succès',
            'data' => $shop->load('template')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop): JsonResponse
    {
        $shop->delete();

        return response()->json([
            'success' => true,
            'message' => 'Boutique supprimée avec succès'
        ]);
    }

    /**
     * Obtenir une boutique par son domaine
     */
    public function getByDomain(Request $request): JsonResponse
    {
        $domain = $request->getHost();
        
        $shop = Shop::where('domain', $domain)
            ->orWhere('domain', 'www.' . $domain)
            ->with('template')
            ->active()
            ->first();

        if (!$shop) {
            return response()->json([
                'success' => false,
                'message' => 'Boutique non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $shop
        ]);
    }
}
