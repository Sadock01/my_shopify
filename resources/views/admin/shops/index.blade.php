@extends('admin.layout')

@section('title', 'Gestion des Boutiques')
@section('page-title', 'Gestion des Boutiques')
@section('page-description', 'Gérez toutes vos boutiques et leurs paramètres')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold text-gray-900">Liste des Boutiques</h2>
    <a href="{{ route('admin.shops.create') }}" 
       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
        + Créer une Boutique
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Boutique
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        URL
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Produits
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Commandes
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
                @forelse($shops as $shop)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($shop->logo)
                                <img src="{{ Storage::url($shop->logo) }}" 
                                     alt="Logo {{ $shop->name }}" 
                                     class="w-10 h-10 rounded-lg object-cover mr-3">
                            @else
                                <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $shop->name }}</div>
                                <div class="text-sm text-gray-500">{{ $shop->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $shop->products_count ?? 0 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $shop->orders_count ?? 0 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($shop->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button onclick="toggleShopStatus({{ $shop->id }})" 
                                    class="text-purple-600 hover:text-purple-900 toggle-status-btn"
                                    data-shop-id="{{ $shop->id }}"
                                    data-current-status="{{ $shop->is_active ? 'active' : 'inactive' }}">
                                {{ $shop->is_active ? 'Désactiver' : 'Activer' }}
                            </button>
                            <a href="{{ route('admin.shops.edit', $shop) }}" 
                               class="text-blue-600 hover:text-blue-900">Modifier</a>
                            <a href="{{ route('admin.shops.manage', $shop) }}" 
                               class="text-green-600 hover:text-green-900">Gérer</a>
                            <form method="POST" action="{{ route('admin.shops.destroy', $shop) }}" 
                                  class="inline" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette boutique ?')">
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
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
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
                const statusCell = document.querySelector(`[data-shop-id="${shopId}"]`).closest('tr').querySelector('td:nth-child(5)`);
                const button = document.querySelector(`[data-shop-id="${shopId}"]`);
                
                if (data.is_active) {
                    statusCell.innerHTML = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>';
                    button.textContent = 'Désactiver';
                    button.setAttribute('data-current-status', 'active');
                } else {
                    statusCell.innerHTML = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactive</span>';
                    button.textContent = 'Activer';
                    button.setAttribute('data-current-status', 'inactive');
                }
                
                // Afficher un message de succès
                alert(data.message);
            } else {
                alert('Erreur lors de la modification du statut');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la modification du statut');
        });
    }
}
</script>
@endsection
