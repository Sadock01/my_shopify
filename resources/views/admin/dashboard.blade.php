@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Vue d\'ensemble de votre plateforme e-commerce')

@section('content')
<!-- Statistiques -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
    <!-- Total Boutiques -->
    <div class="bg-white p-4 sm:p-6 rounded-xl stat-card-shadow border border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-xs sm:text-sm font-medium text-gray-600">Total Boutiques</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $totalShops }}</p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>
        <div class="mt-3 sm:mt-4">
            <span class="text-xs sm:text-sm text-gray-500">Gérez toutes vos boutiques</span>
        </div>
    </div>

    <!-- Boutiques Actives -->
    <div class="bg-white p-4 sm:p-6 rounded-xl stat-card-shadow border border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-xs sm:text-sm font-medium text-gray-600">Boutiques Actives</p>
                <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ $activeShops }}</p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-3 sm:mt-4">
            <span class="text-xs sm:text-sm text-gray-500">En ligne et fonctionnelles</span>
        </div>
    </div>

    <!-- Total Produits -->
    <div class="bg-white p-4 sm:p-6 rounded-xl stat-card-shadow border border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-xs sm:text-sm font-medium text-gray-600">Total Produits</p>
                <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ $totalProducts }}</p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
        </div>
        <div class="mt-3 sm:mt-4">
            <span class="text-xs sm:text-sm text-gray-500">Tous produits confondus</span>
        </div>
    </div>

    <!-- Total Commandes -->
    <div class="bg-white p-4 sm:p-6 rounded-xl stat-card-shadow border border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-xs sm:text-sm font-medium text-gray-600">Total Commandes</p>
                <p class="text-2xl sm:text-3xl font-bold text-orange-600">{{ $totalOrders }}</p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-3 sm:mt-4">
            <span class="text-xs sm:text-sm text-gray-500">Commandes traitées</span>
        </div>
    </div>
</div>

<!-- Actions Rapides -->
<div class="bg-white p-4 sm:p-6 rounded-xl card-shadow border border-gray-200 mb-6 sm:mb-8">
    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
        <a href="{{ route('admin.shops.create') }}" 
           class="flex items-center p-3 sm:p-4 bg-blue-50 hover:bg-blue-100 rounded-lg action-card-shadow transition-colors group">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3 sm:mr-4 group-hover:bg-blue-700 transition-colors flex-shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="font-medium text-gray-900 text-sm sm:text-base">Créer une Boutique</h4>
                <p class="text-xs sm:text-sm text-gray-600">Ajouter une nouvelle boutique</p>
            </div>
        </a>

        <a href="{{ route('admin.templates.index') }}" 
           class="flex items-center p-3 sm:p-4 bg-purple-50 hover:bg-purple-100 rounded-lg action-card-shadow transition-colors group">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-600 rounded-lg flex items-center justify-center mr-3 sm:mr-4 group-hover:bg-purple-700 transition-colors flex-shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="font-medium text-gray-900 text-sm sm:text-base">Gérer les Templates</h4>
                <p class="text-xs sm:text-sm text-gray-600">Personnaliser les designs</p>
            </div>
        </a>

        <a href="{{ route('admin.shops.index') }}" 
           class="flex items-center p-3 sm:p-4 bg-green-50 hover:bg-green-100 rounded-lg action-card-shadow transition-colors group">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3 sm:mr-4 group-hover:bg-green-700 transition-colors flex-shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="font-medium text-gray-900 text-sm sm:text-base">Voir les Statistiques</h4>
                <p class="text-xs sm:text-sm text-gray-600">Analyser les performances</p>
            </div>
        </a>
    </div>
</div>

<!-- Liste des Boutiques Récentes -->
<div class="bg-white rounded-xl table-shadow border border-gray-200">
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Boutiques Récentes</h3>
            <a href="{{ route('admin.shops.index') }}" 
               class="text-blue-600 hover:text-blue-700 font-medium text-xs sm:text-sm">
                Voir toutes →
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[600px]">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Boutique
                    </th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        URL
                    </th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Produits
                    </th>
                    <th class="hidden md:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Commandes
                    </th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                    </th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($shops->take(5) as $shop)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($shop->logo)
                                <img src="{{ Storage::url($shop->logo) }}" 
                                     alt="Logo {{ $shop->name }}" 
                                     class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg object-cover mr-2 sm:mr-3 flex-shrink-0">
                            @else
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-200 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <div class="text-xs sm:text-sm font-medium text-gray-900 truncate">{{ $shop->name }}</div>
                                <div class="text-xs sm:text-sm text-gray-500 truncate">{{ $shop->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                        <div class="flex flex-col space-y-1">
                            <a href="{{ route('shop.home.slug', ['shop' => $shop->slug]) }}" 
                               target="_blank"
                               class="text-blue-600 hover:text-blue-800 text-xs font-mono bg-blue-50 px-2 py-1 rounded">
                                {{ url('/shop/' . $shop->slug) }}
                            </a>
                            @if($shop->domain)
                                <a href="http://{{ $shop->domain }}" 
                                   target="_blank"
                                   class="text-green-600 hover:text-green-800 text-xs font-mono bg-green-50 px-2 py-1 rounded">
                                    {{ $shop->domain }}
                                </a>
                            @endif
                        </div>
                    </td>
                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">
                        {{ $shop->products_count ?? 0 }}
                    </td>
                    <td class="hidden md:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">
                        {{ $shop->orders_count ?? 0 }}
                    </td>
                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                        @if($shop->is_active)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium">
                        <div class="flex flex-col sm:flex-row space-y-1 sm:space-y-0 sm:space-x-2">
                            <button onclick="toggleShopStatus({{ $shop->id }})" 
                                    class="text-purple-600 hover:text-purple-900 toggle-status-btn text-left"
                                    data-shop-id="{{ $shop->id }}"
                                    data-current-status="{{ $shop->is_active ? 'active' : 'inactive' }}">
                                {{ $shop->is_active ? 'Désactiver' : 'Activer' }}
                            </button>
                            <a href="{{ route('admin.shops.edit', $shop) }}" 
                               class="text-blue-600 hover:text-blue-900">Modifier</a>
                            <a href="{{ route('admin.shops.manage', $shop) }}" 
                               class="text-green-600 hover:text-green-900">Gérer</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-3 sm:px-6 py-4 text-center text-gray-500 text-sm">
                        Aucune boutique créée pour le moment.
                        <a href="{{ route('admin.shops.create') }}" class="text-blue-600 hover:text-blue-700 ml-1">
                            Créer votre première boutique
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleShopStatus(shopId) {
    if (confirm('Êtes-vous sûr de vouloir changer le statut de cette boutique ?')) {
        fetch(`/admin/shops/${shopId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour le statut dans le tableau
                const statusCell = document.querySelector(`[data-shop-id="${shopId}"]`).closest('tr').querySelector('td:nth-child(4)`);
                const button = document.querySelector(`[data-shop-id="${shopId}"]`);
                
                if (data.is_active) {
                    statusCell.innerHTML = '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>';
                    button.textContent = 'Désactiver';
                    button.setAttribute('data-current-status', 'active');
                } else {
                    statusCell.innerHTML = '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactive</span>';
                    button.textContent = 'Activer';
                    button.setAttribute('data-current-status', 'inactive');
                }
                
                // Mettre à jour les statistiques en haut
                updateShopStats();
                
                // Afficher un message de succès
                showNotification(data.message, 'success');
            } else {
                showNotification('Erreur lors de la modification du statut', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erreur lors de la modification du statut', 'error');
        });
    }
}

function updateShopStats() {
    // Recharger la page pour mettre à jour les statistiques
    // Ou faire un appel AJAX pour mettre à jour seulement les stats
    location.reload();
}

function showNotification(message, type) {
    // Créer une notification temporaire
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection