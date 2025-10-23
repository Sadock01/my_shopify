@extends('admin.layout')

@section('title', 'Détails de la Commande')
@section('page-title', 'Détails de la Commande')
@section('page-description', 'Consultez les détails complets de la commande et la preuve de paiement')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Commande {{ $order->order_number }}</h2>
        <p class="text-sm text-gray-500">Créée le {{ $order->created_at->format('d/m/Y à H:i') }}</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.orders.index') }}" 
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
            ← Retour à la liste
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informations de la commande -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Informations client -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations Client</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                    <p class="text-gray-900">{{ $order->customer_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <p class="text-gray-900">{{ $order->customer_email }}</p>
                </div>
                @if($order->customer_phone)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                    <p class="text-gray-900">{{ $order->customer_phone }}</p>
                </div>
                @endif
                @if($order->customer_address)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                    <p class="text-gray-900">{{ $order->customer_address }}</p>
                </div>
                @endif
                @if($order->user)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Compte utilisateur</label>
                    <p class="text-blue-600">Connecté ({{ $order->user->name }})</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Articles commandés -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Articles Commandés</h3>
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        @if(isset($item['product_image']))
                            <img src="{{ Storage::url($item['product_image']) }}" 
                                 alt="{{ $item['product_name'] }}" 
                                 class="w-16 h-16 object-cover rounded-lg">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">{{ $item['product_name'] }}</h4>
                        <p class="text-sm text-gray-500">Quantité: {{ $item['quantity'] }}</p>
                        @if(isset($item['size']))
                            <p class="text-sm text-gray-500">Taille: {{ $item['size'] }}</p>
                        @endif
                        @if(isset($item['color']))
                            <p class="text-sm text-gray-500">Couleur: {{ $item['color'] }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">{{ number_format($item['price'] * $item['quantity'], 2) }} €</p>
                        <p class="text-sm text-gray-500">{{ number_format($item['price'], 2) }} € × {{ $item['quantity'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-6 pt-4 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-gray-900">Total</span>
                    <span class="text-xl font-bold text-gray-900">{{ number_format($order->total_amount, 2) }} €</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Statut de la commande -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Statut de la Commande</h3>
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Statut actuel</label>
                        <select name="status" class="w-full border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>En cours</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Expédiée</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Livrée</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (optionnel)</label>
                        <textarea name="notes" 
                                  rows="3" 
                                  class="w-full border-gray-300 rounded-md focus:ring-primary focus:border-primary"
                                  placeholder="Ajoutez des notes sur cette commande...">{{ $order->notes }}</textarea>
                    </div>
                    <button type="submit" 
                            class="w-full bg-primary text-white py-2 px-4 rounded-md hover:opacity-90 transition-opacity">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>

        <!-- Preuve de paiement -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Preuve de Paiement</h3>
            @if($order->payment_proof)
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm text-green-600 font-medium">Preuve fournie</span>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.orders.view-proof', $order) }}" 
                           target="_blank"
                           class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors text-center">
                            Voir
                        </a>
                        <a href="{{ route('admin.orders.download-proof', $order) }}" 
                           class="flex-1 bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors text-center">
                            Télécharger
                        </a>
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <p class="text-sm text-gray-500">Aucune preuve de paiement fournie</p>
                </div>
            @endif
        </div>

        <!-- Informations boutique -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Boutique</h3>
            <div class="flex items-center space-x-3">
                @if($order->shop->logo)
                    <img src="{{ Storage::url($order->shop->logo) }}" 
                         alt="Logo {{ $order->shop->name }}" 
                         class="w-10 h-10 rounded-lg object-cover">
                @else
                    <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                @endif
                <div>
                    <div class="font-medium text-gray-900">{{ $order->shop->name }}</div>
                    <div class="text-sm text-gray-500">{{ $order->shop->slug }}</div>
                </div>
            </div>
        </div>
        
        <!-- Actions de suppression -->
        <div class="bg-red-50 border border-red-200 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-red-900 mb-4">Zone de danger</h3>
            <p class="text-red-700 mb-4">La suppression d'une commande est irréversible. Cela supprimera également la preuve de paiement associée.</p>
            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" 
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ? Cette action est irréversible.')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-colors">
                    Supprimer cette commande
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
