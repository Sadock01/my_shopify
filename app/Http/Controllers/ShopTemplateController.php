<?php

namespace App\Http\Controllers;

use App\Models\ShopTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ShopTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $templates = ShopTemplate::active()->get();
        
        return response()->json([
            'success' => true,
            'data' => $templates
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
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:shop_templates,slug',
            'description' => 'required|string',
            'preview_image' => 'required|string',
            'theme_colors' => 'required|array',
            'layout_options' => 'required|array',
        ]);

        $template = ShopTemplate::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Template créé avec succès',
            'data' => $template
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShopTemplate $template): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $template
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
    public function update(Request $request, ShopTemplate $template): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:shop_templates,slug,' . $template->id,
            'description' => 'sometimes|string',
            'preview_image' => 'sometimes|string',
            'theme_colors' => 'sometimes|array',
            'layout_options' => 'sometimes|array',
        ]);

        $template->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Template mis à jour avec succès',
            'data' => $template
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShopTemplate $template): JsonResponse
    {
        $template->delete();

        return response()->json([
            'success' => true,
            'message' => 'Template supprimé avec succès'
        ]);
    }
}
