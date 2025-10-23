<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentProofController extends Controller
{
    /**
     * Afficher le formulaire d'upload de preuve de paiement
     */
    public function showUploadForm(Request $request, Shop $shop = null)
    {
        // Si le shop n'est pas injecté (routes avec domaine), le récupérer depuis les attributs
        if (!$shop) {
            $shop = $request->attributes->get('current_shop');
            
            if (!$shop) {
                abort(404);
            }
        }

        // Récupérer la dernière commande de l'utilisateur connecté pour cette boutique
        $order = Order::where('user_id', Auth::id())
                     ->where('shop_id', $shop->id)
                     ->whereNull('payment_proof')
                     ->latest()
                     ->first();

        if (!$order) {
            return redirect()->route('shop.home')->with('error', 'Aucune commande en attente de paiement.');
        }

        return view('shop.upload-payment-proof', compact('order', 'shop'));
    }

    /**
     * Traiter l'upload de la preuve de paiement
     */
    public function uploadProof(Request $request, Shop $shop = null)
    {
        // Si le shop n'est pas injecté (routes avec domaine), le récupérer depuis les attributs
        if (!$shop) {
            $shop = $request->attributes->get('current_shop');
            
            if (!$shop) {
                abort(404);
            }
        }

        // Récupérer la dernière commande de l'utilisateur connecté pour cette boutique
        $order = Order::where('user_id', Auth::id())
                     ->where('shop_id', $shop->id)
                     ->whereNull('payment_proof')
                     ->latest()
                     ->first();

        if (!$order) {
            return redirect()->route('shop.home')->with('error', 'Aucune commande en attente de paiement.');
        }

        $validated = $request->validate([
            'payment_proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120', // 5MB max
        ]);

        try {
            // Upload du fichier
            $filePath = $request->file('payment_proof')->store(
                "shops/{$order->shop->slug}/payment-proofs", 
                'public'
            );

            // Mettre à jour la commande avec le chemin du fichier
            $order->update([
                'payment_proof' => $filePath,
                'status' => 'confirmed' // Changer le statut en "confirmé" après upload
            ]);

            return redirect()->route('shop.payment-success')->with('success', 'Preuve de paiement envoyée avec succès ! Votre commande sera traitée sous peu.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'upload du fichier. Veuillez réessayer.');
        }
    }

    /**
     * Afficher la page de succès après upload
     */
    public function paymentSuccess(Request $request, Shop $shop = null)
    {
        // Si le shop n'est pas injecté (routes avec domaine), le récupérer depuis les attributs
        if (!$shop) {
            $shop = $request->attributes->get('current_shop');
            
            if (!$shop) {
                abort(404);
            }
        }

        return view('shop.payment-success', compact('shop'));
    }
}
