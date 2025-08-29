@extends('shop.layout')

@section('title', 'Produits')

@section('content')
    <!-- Page Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-gray-900">Produits</h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:w-1/4">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Filtres</h3>
                    
                    <!-- Categories -->
                    <div class="mb-6">
                        <h4 class="font-medium mb-3">Catégories</h4>
                        <div class="space-y-2">
                            @foreach($categories as $category)
                                <label class="flex items-center">
                                    <input type="checkbox" name="category" value="{{ $category->slug }}" class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="ml-2 text-sm text-gray-700">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Availability -->
                    <div class="mb-6">
                        <h4 class="font-medium mb-3">Disponibilité</h4>
                        <select name="availability" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">Tous</option>
                            <option value="in_stock">En stock</option>
                            <option value="out_of_stock">Rupture de stock</option>
                        </select>
                    </div>
                    
                    <!-- Color Filter -->
                    <div class="mb-6">
                        <h4 class="font-medium mb-3">Couleur</h4>
                        <select name="color" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">Toutes les couleurs</option>
                            <option value="black">Noir</option>
                            <option value="white">Blanc</option>
                            <option value="blue">Bleu</option>
                            <option value="red">Rouge</option>
                            <option value="green">Vert</option>
                        </select>
                    </div>
                    
                    <!-- Apply Filters Button -->
                    <button onclick="applyFilters()" class="w-full bg-primary text-white py-2 px-4 rounded-md hover:opacity-90 transition-opacity">
                        Appliquer les filtres
                    </button>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="lg:w-3/4">
                <!-- Products Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
                    <div class="mb-4 sm:mb-0">
                        <p class="text-gray-600">{{ $products->total() }} produits trouvés</p>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Sort -->
                        <select name="sort" onchange="sortProducts(this.value)" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">Trier par</option>
                            <option value="newest">Plus récents</option>
                            <option value="price_low">Prix croissant</option>
                            <option value="price_high">Prix décroissant</option>
                            <option value="name">Nom A-Z</option>
                        </select>
                        
                        <!-- View Toggle -->
                        <div class="flex border border-gray-300 rounded-md">
                            <button onclick="setView('grid')" class="p-2 border-r border-gray-300 hover:bg-gray-50" id="grid-view">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm0 11h7v7h-7v-7z"/>
                                </svg>
                            </button>
                            <button onclick="setView('list')" class="p-2 hover:bg-gray-50" id="list-view">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div id="products-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @forelse($products as $product)
                        <div class="group bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                            <div class="relative">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                                
                                <!-- Quick Add Button -->
                                <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick="addToCart({{ $product->id }})" class="bg-white text-gray-900 p-2 rounded-full shadow-lg hover:bg-gray-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </button>
                                </div>
                                
                                <!-- Original Price Badge -->
                                @if($product->original_price && $product->original_price > $product->price)
                                    <div class="absolute top-4 left-4">
                                        <span class="bg-red-500 text-white px-2 py-1 rounded text-sm font-medium">
                                            -{{ round((($product->original_price - $product->price) / $product->original_price) * 100) }}%
                                        </span>
                                    </div>
                                @endif
                                
                                <!-- Stock Status -->
                                @if($product->stock <= 0)
                                    <div class="absolute top-4 right-4">
                                        <span class="bg-gray-500 text-white px-2 py-1 rounded text-sm font-medium">
                                            Rupture
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($product->description, 80) }}</p>
                                
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center space-x-2">
                                        @if($product->original_price && $product->original_price > $product->price)
                                            <span class="text-gray-500 line-through text-sm">{{ number_format($product->original_price, 2) }}€</span>
                                        @endif
                                        <span class="text-xl font-bold text-primary">{{ number_format($product->price, 2) }}€</span>
                                    </div>
                                    
                                    <a href="{{ $shop->isOnCustomDomain() ? route('shop.product', $product->id) : route('shop.product.slug', [$shop->slug, $product->id]) }}" class="text-primary hover:underline text-sm">
                                        Voir détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun produit trouvé</h3>
                            <p class="text-gray-500">Essayez de modifier vos filtres ou de revenir plus tard.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function applyFilters() {
        const categories = Array.from(document.querySelectorAll('input[name="category"]:checked')).map(cb => cb.value);
        const availability = document.querySelector('select[name="availability"]').value;
        const color = document.querySelector('select[name="color"]').value;
        
        const params = new URLSearchParams();
        if (categories.length > 0) params.append('category', categories.join(','));
        if (availability) params.append('availability', availability);
        if (color) params.append('color', color);
        
        window.location.href = '{{ $shop->isOnCustomDomain() ? route("shop.products") : route("shop.products.slug", $shop->slug) }}?' + params.toString();
    }
    
    function sortProducts(sort) {
        if (sort) {
            const url = new URL(window.location);
            url.searchParams.set('sort', sort);
            window.location.href = url.toString();
        }
    }
    
    function setView(view) {
        const container = document.getElementById('products-container');
        const gridBtn = document.getElementById('grid-view');
        const listBtn = document.getElementById('list-view');
        
        if (view === 'grid') {
            container.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6';
            gridBtn.classList.add('bg-gray-100');
            listBtn.classList.remove('bg-gray-100');
        } else {
            container.className = 'space-y-4';
            listBtn.classList.add('bg-gray-100');
            gridBtn.classList.remove('bg-gray-100');
        }
    }
    
    // Initialize grid view as default
    document.addEventListener('DOMContentLoaded', function() {
        setView('grid');
    });
</script>
@endpush 