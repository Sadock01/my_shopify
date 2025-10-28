@extends('admin.layout')

@section('title', 'Détails de la Boutique')
@section('page-title', 'Détails de la Boutique')
@section('page-description', 'Consultez les informations détaillées de la boutique')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold text-gray-900">{{ $shop->name }}</h2>
    <div class="flex space-x-3">
        <a href="{{ route('admin.shops.edit', $shop) }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            Modifier
        </a>
        <a href="{{ route('admin.shops.index') }}" 
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
            Retour à la liste
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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informations principales -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Informations générales -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations Générales</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la boutique</label>
                    <p class="text-gray-900">{{ $shop->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <p class="text-gray-900 font-mono">{{ $shop->slug }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Template</label>
                    <p class="text-gray-900">{{ $shop->template ?? 'Par défaut' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $shop->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $shop->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <p class="text-gray-900">{{ $shop->description }}</p>
                </div>
            </div>
        </div>

        <!-- Images -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Images</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @if($shop->logo)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                    <img src="{{ asset('documents/' . $shop->logo) }}" alt="Logo" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                </div>
                @endif
                @if($shop->favicon)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                    <img src="{{ asset('documents/' . $shop->favicon) }}" alt="Favicon" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                </div>
                @endif
                @if($shop->banner_image)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bannière</label>
                    <img src="{{ asset('documents/' . $shop->banner_image) }}" alt="Bannière" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                </div>
                @endif
            </div>
        </div>

        <!-- Informations de paiement -->
        @if($shop->account_holder || $shop->iban || $shop->bic)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations de Paiement</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($shop->account_holder)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Titulaire du compte</label>
                    <p class="text-gray-900">{{ $shop->account_holder }}</p>
                </div>
                @endif
                @if($shop->iban)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">IBAN</label>
                    <p class="text-gray-900 font-mono">{{ $shop->iban }}</p>
                </div>
                @endif
                @if($shop->bic)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">BIC/SWIFT</label>
                    <p class="text-gray-900 font-mono">{{ $shop->bic }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Statistiques -->
    <div class="space-y-6">
        <!-- Statistiques générales -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistiques</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Produits</span>
                    <span class="text-lg font-semibold text-gray-900">{{ $shop->products->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Catégories</span>
                    <span class="text-lg font-semibold text-gray-900">{{ $shop->categories->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Commandes</span>
                    <span class="text-lg font-semibold text-gray-900">{{ $shop->orders->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Témoignages</span>
                    <span class="text-lg font-semibold text-gray-900">{{ $shop->testimonials->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.shops.manage', $shop) }}" 
                   class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-center block">
                    Gérer la boutique
                </a>
                <a href="{{ route('admin.shops.products.index', $shop) }}" 
                   class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-center block">
                    Voir les produits
                </a>
                <a href="{{ route('admin.shops.orders', $shop) }}" 
                   class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors text-center block">
                    Voir les commandes
                </a>
            </div>
        </div>

        <!-- Informations de contact -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact</h3>
            <div class="space-y-2">
                @if($shop->contact_email)
                <div>
                    <span class="text-sm text-gray-600">Email:</span>
                    <p class="text-gray-900">{{ $shop->contact_email }}</p>
                </div>
                @endif
                @if($shop->contact_phone)
                <div>
                    <span class="text-sm text-gray-600">Téléphone:</span>
                    <p class="text-gray-900">{{ $shop->contact_phone }}</p>
                </div>
                @endif
                @if($shop->website)
                <div>
                    <span class="text-sm text-gray-600">Site web:</span>
                    <a href="{{ $shop->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">{{ $shop->website }}</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Dernières commandes -->
@if($shop->orders->count() > 0)
<div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Dernières Commandes</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Commande</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($shop->orders->take(5) as $order)
                <tr>
                    <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ $order->order_number }}</td>
                    <td class="px-4 py-2 text-sm text-gray-900">{{ $order->customer_name }}</td>
                    <td class="px-4 py-2 text-sm text-gray-900">{{ number_format($order->total_amount, 2) }} €</td>
                    <td class="px-4 py-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'confirmed') bg-green-100 text-green-800
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $order->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
