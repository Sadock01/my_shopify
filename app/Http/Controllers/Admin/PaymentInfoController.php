<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentInfoController extends Controller
{
    /**
     * Afficher les informations de paiement d'une boutique
     */
    public function index(Shop $shop)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->is_admin) {
            abort(403, 'Accès non autorisé');
        }

        return view('admin.payment-info.index', compact('shop'));
    }

    /**
     * Mettre à jour les informations de paiement d'une boutique
     */
    public function update(Request $request, Shop $shop)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->is_admin) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'bank_name' => 'nullable|string|max:255',
            'account_holder' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:34',
            'bic' => 'nullable|string|max:11',
            'payment_instructions' => 'nullable|string',
            'payment_methods' => 'nullable|array',
            'payment_methods.*' => 'string|max:255'
        ]);

        // Nettoyer les méthodes de paiement (enlever les valeurs vides)
        if (isset($validated['payment_methods'])) {
            $validated['payment_methods'] = array_filter($validated['payment_methods'], function($value) {
                return !empty(trim($value));
            });
        }

        $shop->update($validated);

        return redirect()->route('admin.payment-info.index', $shop)
            ->with('success', 'Informations de paiement mises à jour avec succès !');
    }
}
