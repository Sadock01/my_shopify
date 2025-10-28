<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Afficher la liste des moyens de paiement d'une boutique
     */
    public function index(Shop $shop)
    {
        $paymentMethods = $shop->paymentMethods()->orderBy('sort_order')->get();
        return view('admin.payment-methods.index', compact('shop', 'paymentMethods'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Shop $shop)
    {
        $types = [
            'bank_transfer' => 'Virement bancaire',
            'check' => 'Chèque',
            'cash' => 'Espèces',
            'mobile_money' => 'Mobile Money',
            'paypal' => 'PayPal',
            'stripe' => 'Stripe',
            'other' => 'Autre'
        ];
        
        return view('admin.payment-methods.create', compact('shop', 'types'));
    }

    /**
     * Enregistrer un nouveau moyen de paiement
     */
    public function store(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:bank_transfer,check,cash,mobile_money,paypal,stripe,other',
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'details' => 'nullable|array',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        // Upload de l'icône si fournie
        if ($request->hasFile('icon')) {
            $destinationPath = public_path('documents/shops/' . $shop->slug . '/payment-methods');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $filename = uniqid() . '.' . $request->file('icon')->getClientOriginalExtension();
            $request->file('icon')->move($destinationPath, $filename);
            $iconPath = 'shops/' . $shop->slug . '/payment-methods/' . $filename;
            $validated['icon'] = $iconPath;
        }

        // Définir l'ordre si non fourni
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = $shop->paymentMethods()->max('sort_order') + 1;
        }

        $shop->paymentMethods()->create($validated);

        return redirect()->route('admin.shops.payment-methods.index', $shop)
            ->with('success', 'Moyen de paiement ajouté avec succès !');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Shop $shop, PaymentMethod $paymentMethod)
    {
        $types = [
            'bank_transfer' => 'Virement bancaire',
            'check' => 'Chèque',
            'cash' => 'Espèces',
            'mobile_money' => 'Mobile Money',
            'paypal' => 'PayPal',
            'stripe' => 'Stripe',
            'other' => 'Autre'
        ];
        
        return view('admin.payment-methods.edit', compact('shop', 'paymentMethod', 'types'));
    }

    /**
     * Mettre à jour un moyen de paiement
     */
    public function update(Request $request, Shop $shop, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:bank_transfer,check,cash,mobile_money,paypal,stripe,other',
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'details' => 'nullable|array',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        // Upload de la nouvelle icône si fournie
        if ($request->hasFile('icon')) {
            if ($paymentMethod->icon) {
                $oldImagePath = public_path('documents/' . $paymentMethod->icon);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            $destinationPath = public_path('documents/shops/' . $shop->slug . '/payment-methods');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $filename = uniqid() . '.' . $request->file('icon')->getClientOriginalExtension();
            $request->file('icon')->move($destinationPath, $filename);
            $iconPath = 'shops/' . $shop->slug . '/payment-methods/' . $filename;
            $validated['icon'] = $iconPath;
        }

        $paymentMethod->update($validated);

        return redirect()->route('admin.shops.payment-methods.index', $shop)
            ->with('success', 'Moyen de paiement mis à jour avec succès !');
    }

    /**
     * Supprimer un moyen de paiement
     */
    public function destroy(Shop $shop, PaymentMethod $paymentMethod)
    {
        // Supprimer l'icône si elle existe
        if ($paymentMethod->icon) {
            $imagePath = public_path('documents/' . $paymentMethod->icon);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $paymentMethod->delete();

        return redirect()->route('admin.shops.payment-methods.index', $shop)
            ->with('success', 'Moyen de paiement supprimé avec succès !');
    }
}
