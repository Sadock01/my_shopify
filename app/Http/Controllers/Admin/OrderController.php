<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Afficher la liste des commandes
     */
    public function index()
    {
        $orders = Order::with(['shop', 'user'])
                      ->latest()
                      ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Afficher les commandes d'une boutique spécifique
     */
    public function shopOrders(Shop $shop)
    {
        $orders = $shop->orders()
                      ->with('user')
                      ->latest()
                      ->paginate(20);

        return view('admin.orders.shop-orders', compact('orders', 'shop'));
    }

    /**
     * Afficher les détails d'une commande
     */
    public function show(Order $order)
    {
        $order->load(['shop', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Mettre à jour le statut d'une commande
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'notes' => 'nullable|string|max:1000'
        ]);

        $order->update($validated);

        return redirect()->back()->with('success', 'Statut de la commande mis à jour avec succès !');
    }

    /**
     * Télécharger la preuve de paiement
     */
    public function downloadPaymentProof(Order $order)
    {
        if (!$order->payment_proof) {
            return redirect()->back()->with('error', 'Aucune preuve de paiement disponible pour cette commande.');
        }

        $filePath = storage_path('app/public/' . $order->payment_proof);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Le fichier de preuve de paiement n\'existe plus.');
        }

        return response()->download($filePath);
    }

    /**
     * Afficher la preuve de paiement
     */
    public function viewPaymentProof(Order $order)
    {
        if (!$order->payment_proof) {
            return redirect()->back()->with('error', 'Aucune preuve de paiement disponible pour cette commande.');
        }

        $filePath = storage_path('app/public/' . $order->payment_proof);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Le fichier de preuve de paiement n\'existe plus.');
        }

        $mimeType = mime_content_type($filePath);
        
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($order->payment_proof) . '"'
        ]);
    }

    /**
     * Supprimer une commande
     */
    public function destroy(Order $order)
    {
        try {
            // Supprimer le fichier de preuve de paiement s'il existe
            if ($order->payment_proof) {
                $filePath = storage_path('app/public/' . $order->payment_proof);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $order->delete();

            return redirect()->route('admin.orders.index')->with('success', 'Commande supprimée avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression de la commande : ' . $e->getMessage());
        }
    }
}
