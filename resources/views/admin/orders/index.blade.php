@extends('admin.layout')

@section('title', 'Gestion des Commandes')
@section('page-title', 'Gestion des Commandes')
@section('page-description', 'Consultez et gérez toutes les commandes avec leurs preuves de paiement')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold text-gray-900">Liste des Commandes</h2>
    <div class="flex space-x-3">
        <a href="{{ route('admin.shops.index') }}" 
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
            Voir les Boutiques
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Commande
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Client
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Boutique
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Montant
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Preuve de Paiement
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col">
                            <div class="text-sm font-medium text-gray-900">{{ $order->order_number }}</div>
                            <div class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col">
                            <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
                            @if($order->user)
                                <div class="text-xs text-blue-600">Utilisateur connecté</div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($order->shop->logo)
                                <img src="{{ Storage::url($order->shop->logo) }}" 
                                     alt="Logo {{ $order->shop->name }}" 
                                     class="w-8 h-8 rounded-lg object-cover mr-3">
                            @else
                                <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $order->shop->name }}</div>
                                <div class="text-sm text-gray-500">{{ $order->shop->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ number_format($order->total_amount, 2) }} €</div>
                        <div class="text-sm text-gray-500">{{ count($order->items) }} article(s)</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($order->payment_proof)
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex space-x-1">
                                    <a href="{{ route('admin.orders.view-proof', $order) }}" 
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                        Voir
                                    </a>
                                    <a href="{{ route('admin.orders.download-proof', $order) }}" 
                                       class="text-green-600 hover:text-green-800 text-sm">
                                        Télécharger
                                    </a>
                                </div>
                            </div>
                        @else
                            <span class="text-red-500 text-sm">Aucune preuve</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <select name="status" 
                                    onchange="this.form.submit()" 
                                    class="text-sm border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>En cours</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Expédiée</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Livrée</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                            </select>
                        </form>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.orders.show', $order) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                Détails
                            </a>
                            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ? Cette action est irréversible.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <p class="text-lg font-medium">Aucune commande trouvée</p>
                        <p class="text-sm">Les commandes apparaîtront ici une fois que les clients auront passé des commandes.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
