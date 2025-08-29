@extends('shop.layout')

@section('title', 'Boutique')

@section('content')
    <!-- Page Header -->
    <section class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl lg:text-5xl font-light tracking-tight text-black mb-6">Notre boutique</h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Découvrez notre collection complète de produits soigneusement sélectionnés pour vous
                </p>
            </div>
        </div>
    </section>

    <!-- Filters and Products -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:flex lg:gap-12">
                <!-- Sidebar Filters -->
                <div class="lg:w-1/4 mb-8 lg:mb-0">
                    <div class="sticky top-24">
                        <h3 class="text-lg font-medium text-black mb-6">Filtres</h3>
                        
                        <!-- Search -->
                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Recherche</label>
                            <div class="relative">
                                <input type="text" id="search-input" placeholder="Rechercher un produit..." 
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Catégories</label>
                            <div class="space-y-2">
                                @foreach($categories as $category)
                                <label class="flex items-center">
                                    <input type="checkbox" value="{{ $category->id }}" class="category-filter rounded border-gray-300 text-black focus:ring-black">
                                    <span class="ml-3 text-sm text-gray-700">{{ $category->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Prix</label>
                            <div class="space-y-3">
                                <div>
                                    <input type="range" id="price-min" min="0" max="1000" value="0" 
                                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider">
                                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                                        <span>0€</span>
                                        <span id="price-min-value">0€</span>
                                    </div>
                                </div>
                                <div>
                                    <input type="range" id="price-max" min="0" max="1000" value="1000" 
                                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider">
                                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                                        <span id="price-max-value">1000€</span>
                                        <span>1000€</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Clear Filters -->
                        <button id="clear-filters" class="w-full bg-gray-100 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-200 transition-colors duration-200 text-sm font-medium">
                            Effacer les filtres
                        </button>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="lg:w-3/4">
                    <!-- Sort and View Options -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                        <div class="flex items-center space-x-4">
                            <label class="text-sm font-medium text-gray-700">Trier par :</label>
                            <select id="sort-select" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent">
                                <option value="newest">Plus récents</option>
                                <option value="price-low">Prix croissant</option>
                                <option value="price-high">Prix décroissant</option>
                                <option value="name">Nom A-Z</option>
                            </select>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <button id="grid-view" class="p-2 rounded-lg bg-black text-white">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                            </button>
                            <button id="list-view" class="p-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Products Count -->
                    <div class="mb-6">
                        <p class="text-gray-600">
                            <span id="products-count">{{ $products->count() }}</span> produit(s) trouvé(s)
                        </p>
                    </div>

                    <!-- Products Grid -->
                    <div id="products-container" class="products-grid">
                        @forelse($products as $product)
                            <div class="product-card group bg-white border border-gray-100 rounded-2xl overflow-hidden hover-lift" 
                                 data-category="{{ $product->category_id }}" 
                                 data-price="{{ $product->price }}"
                                 data-name="{{ strtolower($product->name) }}">
                                <div class="relative overflow-hidden">
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-700">
                                    
                                    <!-- Quick Add Button -->
                                    <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                                        <button onclick="addToCart({{ $product->id }})" class="bg-black text-white p-3 rounded-full shadow-lg hover:bg-gray-800 transition-all duration-300 hover:scale-110">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Discount Badge -->
                                    @if($product->original_price && $product->original_price > $product->price)
                                        <div class="absolute top-4 left-4">
                                            <span class="bg-black text-white px-3 py-2 text-sm font-medium rounded-full">
                                                -{{ round((($product->original_price - $product->price) / $product->original_price) * 100) }}%
                                            </span>
                                        </div>
                                    @endif
                                    
                                    <!-- Favorite Button -->
                                    <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        <button class="bg-white/90 backdrop-blur-sm text-gray-600 p-2 rounded-full hover:text-red-500 transition-colors duration-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="p-6">
                                    <div class="mb-3">
                                        <span class="text-xs text-gray-500 uppercase tracking-wide">{{ $product->category->name ?? 'Catégorie' }}</span>
                                    </div>
                                    
                                    <h3 class="text-xl font-medium text-black mb-3 group-hover:text-gray-600 transition-colors">{{ $product->name }}</h3>
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">{{ Str::limit($product->description, 80) }}</p>
                                    
                                    <div class="flex justify-between items-end">
                                        <div class="flex flex-col">
                                            @if($product->original_price && $product->original_price > $product->price)
                                                <span class="text-gray-400 line-through text-sm mb-1">{{ number_format($product->original_price, 2) }}€</span>
                                            @endif
                                            <span class="text-lg font-medium text-black">{{ number_format($product->price, 2) }}€</span>
                                        </div>
                                        
                                        <a href="{{ $shop->isOnCustomDomain() ? route('shop.product', $product->id) : route('shop.product.slug', [$shop->slug, $product->id]) }}" 
                                           class="text-black hover:text-gray-600 text-sm font-medium group-hover:underline transition-colors duration-200 whitespace-nowrap">
                                            Voir détails →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-20">
                                <div class="w-24 h-24 bg-gray-100 rounded-full mx-auto mb-8 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-medium text-gray-900 mb-4">Aucun produit trouvé</h3>
                                <p class="text-gray-500 text-lg">Essayez de modifier vos filtres ou revenez plus tard.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                    <div class="mt-16">
                        {{ $products->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    /* Custom slider styles */
    .slider {
        -webkit-appearance: none;
        appearance: none;
        background: #e5e7eb;
        outline: none;
        border-radius: 8px;
    }
    
    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #000;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .slider::-webkit-slider-thumb:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .slider::-moz-range-thumb {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #000;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
    }
    
    .slider::-moz-range-thumb:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    /* List view styles */
    .products-grid.list-view {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .products-grid.list-view .product-card {
        display: flex;
        max-width: none;
    }
    
    .products-grid.list-view .product-card > div:first-child {
        width: 200px;
        flex-shrink: 0;
    }
    
    .products-grid.list-view .product-card > div:last-child {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    @media (max-width: 1024px) {
        .products-grid.list-view .product-card {
            flex-direction: column;
        }
        
        .products-grid.list-view .product-card > div:first-child {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const categoryFilters = document.querySelectorAll('.category-filter');
    const priceMinSlider = document.getElementById('price-min');
    const priceMaxSlider = document.getElementById('price-max');
    const priceMinValue = document.getElementById('price-min-value');
    const priceMaxValue = document.getElementById('price-max-value');
    const sortSelect = document.getElementById('sort-select');
    const clearFiltersBtn = document.getElementById('clear-filters');
    const gridViewBtn = document.getElementById('grid-view');
    const listViewBtn = document.getElementById('list-view');
    const productsContainer = document.getElementById('products-container');
    const productsCount = document.getElementById('products-count');
    
    let currentView = 'grid';
    
    // Price range sliders
    priceMinSlider.addEventListener('input', function() {
        priceMinValue.textContent = this.value + '€';
        filterProducts();
    });
    
    priceMaxSlider.addEventListener('input', function() {
        priceMaxValue.textContent = this.value + '€';
        filterProducts();
    });
    
    // Search input
    searchInput.addEventListener('input', function() {
        filterProducts();
    });
    
    // Category filters
    categoryFilters.forEach(filter => {
        filter.addEventListener('change', filterProducts);
    });
    
    // Sort select
    sortSelect.addEventListener('change', function() {
        filterProducts();
    });
    
    // Clear filters
    clearFiltersBtn.addEventListener('click', function() {
        searchInput.value = '';
        categoryFilters.forEach(filter => filter.checked = false);
        priceMinSlider.value = 0;
        priceMaxSlider.value = 1000;
        priceMinValue.textContent = '0€';
        priceMaxValue.textContent = '1000€';
        sortSelect.value = 'newest';
        filterProducts();
    });
    
    // View toggle
    gridViewBtn.addEventListener('click', function() {
        setView('grid');
    });
    
    listViewBtn.addEventListener('click', function() {
        setView('list');
    });
    
    function setView(view) {
        currentView = view;
        if (view === 'grid') {
            productsContainer.classList.remove('list-view');
            gridViewBtn.className = 'p-2 rounded-lg bg-black text-white';
            listViewBtn.className = 'p-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200';
        } else {
            productsContainer.classList.add('list-view');
            listViewBtn.className = 'p-2 rounded-lg bg-black text-white';
            gridViewBtn.className = 'p-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200';
        }
    }
    
    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategories = Array.from(categoryFilters)
            .filter(filter => filter.checked)
            .map(filter => filter.value);
        const minPrice = parseInt(priceMinSlider.value);
        const maxPrice = parseInt(priceMaxSlider.value);
        const sortBy = sortSelect.value;
        
        const productCards = document.querySelectorAll('.product-card');
        let visibleCount = 0;
        
        productCards.forEach(card => {
            const name = card.dataset.name;
            const category = card.dataset.category;
            const price = parseInt(card.dataset.price);
            
            const matchesSearch = name.includes(searchTerm);
            const matchesCategory = selectedCategories.length === 0 || selectedCategories.includes(category);
            const matchesPrice = price >= minPrice && price <= maxPrice;
            
            if (matchesSearch && matchesCategory && matchesPrice) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Update products count
        productsCount.textContent = visibleCount;
        
        // Sort products
        sortProducts(sortBy);
    }
    
    function sortProducts(sortBy) {
        const productCards = Array.from(document.querySelectorAll('.product-card'));
        const container = productsContainer;
        
        productCards.sort((a, b) => {
            const nameA = a.dataset.name;
            const nameB = b.dataset.name;
            const priceA = parseInt(a.dataset.price);
            const priceB = parseInt(b.dataset.price);
            
            switch(sortBy) {
                case 'name':
                    return nameA.localeCompare(nameB);
                case 'price-low':
                    return priceA - priceB;
                case 'price-high':
                    return priceB - priceA;
                case 'newest':
                default:
                    return 0; // Keep original order
            }
        });
        
        // Reorder DOM elements
        productCards.forEach(card => {
            container.appendChild(card);
        });
    }
    
    // Initialize
    filterProducts();
});
</script>
@endpush 