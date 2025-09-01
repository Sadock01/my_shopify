@extends('admin.layout')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Gérer la Boutique : {{ $shop->name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.shops.payment-methods.index', $shop) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                Moyens de Paiement
            </a>
            <a href="{{ route('admin.shops.edit', $shop) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                Modifier
            </a>
            <a href="{{ route('admin.shops.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                Retour à la liste
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Informations de la boutique -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations Générales</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Nom</p>
                <p class="font-medium">{{ $shop->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Slug</p>
                <p class="font-medium">{{ $shop->slug }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Template</p>
                <p class="font-medium">{{ $shop->template->name ?? 'Template par défaut' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Statut</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $shop->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $shop->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Produits</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $shop->products_count ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Commandes</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $shop->orders_count ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-10 0a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Catégories</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $shop->categories_count ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Catégories -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Catégories</h2>
            <a href="{{ route('admin.shops.categories.create', $shop) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                Ajouter une catégorie
            </a>
        </div>
        
        @if($shop->categories && $shop->categories->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produits</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($shop->categories as $category)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($category->image)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}">
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->slug }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->products_count ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.shops.categories.edit', [$shop, $category]) }}" class="text-blue-600 hover:text-blue-900 mr-3">Modifier</a>
                                <form action="{{ route('admin.shops.categories.destroy', [$shop, $category]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-4">Aucune catégorie trouvée pour cette boutique.</p>
        @endif
    </div>

    <!-- Témoignages -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Témoignages Clients</h2>
            <a href="{{ route('admin.shops.testimonials.create', $shop) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                Ajouter un témoignage
            </a>
        </div>
        
        @if($shop->testimonials && $shop->testimonials->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($shop->testimonials as $testimonial)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </div>
                        @if($testimonial->is_featured)
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Mis en avant
                            </span>
                        @endif
                    </div>
                    <p class="text-gray-700 text-sm mb-3">{{ Str::limit($testimonial->comment, 100) }}</p>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900 text-sm">{{ $testimonial->customer_name }}</p>
                            @if($testimonial->customer_location)
                                <p class="text-gray-500 text-xs">{{ $testimonial->customer_location }}</p>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.shops.testimonials.edit', [$shop, $testimonial]) }}" class="text-blue-600 hover:text-blue-900 text-sm">Modifier</a>
                            <form action="{{ route('admin.shops.testimonials.destroy', [$shop, $testimonial]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce témoignage ?')">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-4">Aucun témoignage trouvé pour cette boutique.</p>
        @endif
    </div>

    <!-- Produits récents -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Produits Récents</h2>
            <a href="{{ route('admin.shops.products.create', $shop) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                Ajouter un produit
            </a>
        </div>
        
        @if($shop->products && $shop->products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($shop->products->take(6) as $product)
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    @if($product->image)
                        <img class="w-full h-48 object-cover" src="{{ $product->image }}" alt="{{ $product->name }}">
                    @endif
                    <div class="p-4">
                        <h3 class="font-medium text-gray-900 mb-2">{{ $product->name }}</h3>
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">
                                @if($product->original_price > $product->price)
                                    <span class="line-through">{{ number_format($product->original_price, 2) }}€</span>
                                @endif
                                <span class="font-medium text-gray-900 ml-2">{{ number_format($product->price, 2) }}€</span>
                            </div>
                            <span class="text-xs text-gray-500">Stock: {{ $product->stock }}</span>
                        </div>
                        <div class="mt-3 flex space-x-2">
                            <a href="{{ route('admin.shops.products.edit', [$shop, $product]) }}" class="text-blue-600 hover:text-blue-900 text-sm">Modifier</a>
                            <form action="{{ route('admin.shops.products.destroy', [$shop, $product]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($shop->products->count() > 6)
                <div class="mt-4 text-center">
                    <a href="{{ route('admin.shops.products.index', $shop) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                        Voir tous les produits ({{ $shop->products->count() }})
                    </a>
                </div>
            @endif
        @else
            <p class="text-gray-500 text-center py-4">Aucun produit trouvé pour cette boutique.</p>
        @endif
    </div>
</div>
@endsection
