@extends('shop.layout')

@section('title', $product->name)

@section('content')
    <!-- Product Details -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-8" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li>
                        <a href="{{ $shop->isOnCustomDomain() ? route('shop.home') : route('shop.home.slug', $shop->slug) }}" 
                           class="hover:text-black transition-colors duration-200">
                            Accueil
                        </a>
                    </li>
                    <li>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </li>
                    <li>
                        <a href="{{ $shop->isOnCustomDomain() ? route('shop.products') : route('shop.products.slug', $shop->slug) }}" 
                           class="hover:text-black transition-colors duration-200">
                            Boutique
                        </a>
                    </li>
                    <li>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </li>
                    <li>
                        <a href="#" class="hover:text-black transition-colors duration-200">
                            {{ $product->category->name ?? 'Catégorie' }}
                        </a>
                    </li>
                    <li>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </li>
                    <li class="text-black font-medium">{{ $product->name }}</li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <!-- Product Images -->
                <div class="space-y-4">
                    <!-- Main Image -->
                    <div class="relative">
                        <img id="main-image" src="{{ asset('documents/' . $product->image) }}" alt="{{ $product->name }}" 
                             class="w-full h-96 lg:h-[500px] object-cover rounded-2xl">
                        
                        <!-- Favorite Button -->
                        <button class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm text-gray-600 p-3 rounded-full hover:text-red-500 transition-all duration-200 hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                        
                        <!-- Discount Badge -->
                        @if($product->original_price && $product->original_price > $product->price)
                            <div class="absolute top-4 left-4">
                                <span class="bg-black text-white px-4 py-2 text-sm font-medium rounded-full">
                                    -{{ round((($product->original_price - $product->price) / $product->original_price) * 100) }}%
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Thumbnail Images -->
                    @if(isset($product->images) && is_array($product->images) && count($product->images) > 1)
                    <div class="flex space-x-4 overflow-x-auto">
                        <button onclick="changeMainImage('{{ $product->image }}')" 
                                class="thumbnail-btn flex-shrink-0 w-20 h-20 border-2 border-black rounded-lg overflow-hidden">
                            <img src="{{ asset('documents/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        </button>
                        @foreach($product->images as $image)
                            @if($image !== $product->image)
                            <button onclick="changeMainImage('{{ $image }}')" 
                                    class="thumbnail-btn flex-shrink-0 w-20 h-20 border-2 border-gray-200 rounded-lg overflow-hidden hover:border-gray-300 transition-colors duration-200">
                                <img src="{{ asset('documents/' . $image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            </button>
                            @endif
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="space-y-8">
                    <!-- Product Header -->
                    <div>
                        <div class="mb-4">
                            <span class="text-sm text-gray-500 uppercase tracking-wide">{{ $product->category->name ?? 'Catégorie' }}</span>
                        </div>
                        
                        <h1 class="text-3xl lg:text-4xl font-light tracking-tight text-black mb-4">{{ $product->name }}</h1>
                        
                        <div class="flex items-center space-x-4 mb-6">
                            @if($product->original_price && $product->original_price > $product->price)
                                <span class="text-2xl text-gray-400 line-through">{{ number_format($product->original_price, 2) }}€</span>
                            @endif
                            <span class="text-4xl font-light text-black">{{ number_format($product->price, 2) }}€</span>
                        </div>
                        
                        <p class="text-gray-600 leading-relaxed text-lg">{{ $product->description }}</p>
                    </div>

                    <!-- Product Options -->
                    <div class="space-y-6">
                        <!-- Sizes -->
                        @if(isset($product->sizes) && is_array($product->sizes) && count($product->sizes) > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Taille</label>
                            <div class="flex flex-wrap gap-3">
                                @foreach($product->sizes as $size)
                                <button class="size-btn px-4 py-2 border border-gray-200 rounded-lg hover:border-black transition-colors duration-200 text-sm font-medium">
                                    {{ $size }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Colors -->
                        @if(isset($product->colors) && is_array($product->colors) && count($product->colors) > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Couleur</label>
                            <div class="flex flex-wrap gap-3">
                                @foreach($product->colors as $color)
                                <button class="color-btn px-4 py-2 border border-gray-200 rounded-lg hover:border-black transition-colors duration-200 text-sm font-medium">
                                    {{ $color }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Quantity -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Quantité</label>
                            <div class="flex items-center space-x-3">
                                <button onclick="changeQuantity(-1)" class="w-10 h-10 border border-gray-200 rounded-lg flex items-center justify-center hover:border-black transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}" 
                                       class="w-20 h-10 border border-gray-200 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent">
                                <button onclick="changeQuantity(1)" class="w-10 h-10 border border-gray-200 rounded-lg flex items-center justify-center hover:border-black transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">{{ $product->stock }} en stock</p>
                        </div>

                        <!-- Add to Cart Button -->
                        <div class="pt-4">
                            <button onclick="addToCart({{ $product->id }}, document.getElementById('quantity').value, {
                                name: '{{ addslashes($product->name) }}',
                                price: {{ $product->price }},
                                image: '{{ $product->image }}',
                                category: '{{ addslashes($product->category->name ?? 'Catégorie') }}'
                            })" 
                                    class="w-full bg-black text-white py-4 px-8 rounded-2xl font-medium tracking-wide hover:bg-gray-800 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <div class="flex items-center justify-center space-x-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                                    </svg>
                                    <span>Ajouter au panier</span>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Product Features -->
                    <div class="border-t border-gray-100 pt-8">
                        <h3 class="text-lg font-medium text-black mb-4">Caractéristiques</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-600">Livraison gratuite</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-600">Retours acceptés</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-600">Garantie</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-600">Support client 24/7</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Details Tabs -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm">
                <!-- Tab Navigation -->
                <div class="border-b border-gray-100">
                    <nav class="flex space-x-8 px-8" aria-label="Tabs">
                        <button class="tab-btn active py-6 px-1 border-b-2 border-black text-sm font-medium text-black" 
                                onclick="showTab('description')">
                            Description
                        </button>
                        <button class="tab-btn py-6 px-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700" 
                                onclick="showTab('specifications')">
                            Spécifications
                        </button>
                        <button class="tab-btn py-6 px-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700" 
                                onclick="showTab('reviews')">
                            Avis (0)
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-8">
                    <!-- Description Tab -->
                    <div id="description-tab" class="tab-content">
                        <div class="prose max-w-none">
                            <h3 class="text-2xl font-light tracking-tight text-black mb-6">À propos de ce produit</h3>
                            <p class="text-gray-600 leading-relaxed text-lg mb-6">{{ $product->description }}</p>
                            
                            @if(isset($product->features) && is_array($product->features))
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                                @foreach($product->features as $feature)
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-black rounded-full mt-2 flex-shrink-0"></div>
                                    <span class="text-gray-700">{{ $feature }}</span>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Specifications Tab -->
                    <div id="specifications-tab" class="tab-content hidden">
                        <div class="space-y-6">
                            <h3 class="text-2xl font-light tracking-tight text-black mb-6">Spécifications techniques</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="flex justify-between py-3 border-b border-gray-100">
                                        <span class="text-gray-600">Catégorie</span>
                                        <span class="font-medium">{{ $product->category->name ?? 'Non spécifiée' }}</span>
                                    </div>
                                    <div class="flex justify-between py-3 border-b border-gray-100">
                                        <span class="text-gray-600">Référence</span>
                                        <span class="font-medium">#{{ $product->id }}</span>
                                    </div>
                                    <div class="flex justify-between py-3 border-b border-gray-100">
                                        <span class="text-gray-600">Stock</span>
                                        <span class="font-medium">{{ $product->stock }} unités</span>
                                    </div>
                                </div>
                                
                                <div class="space-y-4">
                                    @if(isset($product->sizes) && is_array($product->sizes))
                                    <div class="flex justify-between py-3 border-b border-gray-100">
                                        <span class="text-gray-600">Tailles disponibles</span>
                                        <span class="font-medium">{{ implode(', ', $product->sizes) }}</span>
                                    </div>
                                    @endif
                                    
                                    @if(isset($product->colors) && is_array($product->colors))
                                    <div class="flex justify-between py-3 border-b border-gray-100">
                                        <span class="text-gray-600">Couleurs disponibles</span>
                                        <span class="font-medium">{{ implode(', ', $product->colors) }}</span>
                                    </div>
                                    @endif
                                    
                                    <div class="flex justify-between py-3 border-b border-gray-100">
                                        <span class="text-gray-600">Prix</span>
                                        <span class="font-medium">{{ number_format($product->price, 2) }}€</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews Tab -->
                    <div id="reviews-tab" class="tab-content hidden">
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full mx-auto mb-6 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-medium text-gray-900 mb-4">Aucun avis pour le moment</h3>
                            <p class="text-gray-500 text-lg">Soyez le premier à laisser un avis sur ce produit !</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    @if(isset($relatedProducts) && count($relatedProducts) > 0)
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-light tracking-tight text-black mb-6">Produits similaires</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Découvrez d'autres produits qui pourraient vous intéresser
                </p>
            </div>
            
            <div class="products-grid">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="group bg-white border border-gray-100 rounded-2xl overflow-hidden hover-lift">
                        <div class="relative overflow-hidden">
                            <img src="{{ asset('documents/' . $relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-700">
                            
                            <!-- Quick Add Button -->
                            <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                                <button onclick="addToCart({{ $relatedProduct->id }}, 1, {
                                    name: '{{ addslashes($relatedProduct->name) }}',
                                    price: {{ $relatedProduct->price }},
                                    image: '{{ $relatedProduct->image }}',
                                    category: '{{ addslashes($relatedProduct->category->name ?? 'Catégorie') }}'
                                })" class="bg-black text-white p-3 rounded-full shadow-lg hover:bg-gray-800 transition-all duration-300 hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Discount Badge -->
                            @if($relatedProduct->original_price && $relatedProduct->original_price > $relatedProduct->price)
                                <div class="absolute top-4 left-4">
                                    <span class="bg-black text-white px-3 py-2 text-sm font-medium rounded-full">
                                        -{{ round((($relatedProduct->original_price - $relatedProduct->price) / $relatedProduct->original_price) * 100) }}%
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-xl font-medium text-black mb-3 group-hover:text-gray-600 transition-colors">{{ $relatedProduct->name }}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">{{ Str::limit($relatedProduct->description, 80) }}</p>
                            
                            <div class="flex justify-between items-end">
                                <div class="flex flex-col">
                                    @if($relatedProduct->original_price && $relatedProduct->original_price > $relatedProduct->price)
                                        <span class="text-gray-400 line-through text-sm mb-1">{{ number_format($relatedProduct->original_price, 2) }}€</span>
                                    @endif
                                    <span class="text-lg font-medium text-black">{{ number_format($relatedProduct->price, 2) }}€</span>
                                </div>
                                
                                <a href="{{ $shop->isOnCustomDomain() ? route('shop.product', $relatedProduct->id) : route('shop.product.slug', [$shop->slug, $relatedProduct->id]) }}" 
                                   class="text-black hover:text-gray-600 text-sm font-medium group-hover:underline transition-colors duration-200 whitespace-nowrap">
                                    Voir détails →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection

@push('scripts')
<script>
let selectedSize = null;
let selectedColor = null;

function changeMainImage(imageSrc) {
    document.getElementById('main-image').src = imageSrc;
    
    // Update thumbnail borders
    document.querySelectorAll('.thumbnail-btn').forEach(btn => {
        btn.classList.remove('border-black');
        btn.classList.add('border-gray-200');
    });
    
    // Find and highlight the clicked thumbnail
    event.target.closest('.thumbnail-btn').classList.remove('border-gray-200');
    event.target.closest('.thumbnail-btn').classList.add('border-black');
}

function changeQuantity(delta) {
    const quantityInput = document.getElementById('quantity');
    const newValue = parseInt(quantityInput.value) + delta;
    const maxValue = parseInt(quantityInput.max);
    
    if (newValue >= 1 && newValue <= maxValue) {
        quantityInput.value = newValue;
    }
}

function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active', 'border-black', 'text-black');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Activate selected tab button
    event.target.classList.add('active', 'border-black', 'text-black');
    event.target.classList.remove('border-transparent', 'text-gray-500');
}

// Size and color selection
document.addEventListener('DOMContentLoaded', function() {
    // Size buttons
    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('bg-black', 'text-white'));
            this.classList.add('bg-black', 'text-white');
            selectedSize = this.textContent.trim();
        });
    });
    
    // Color buttons
    document.querySelectorAll('.color-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('bg-black', 'text-white'));
            this.classList.add('bg-black', 'text-white');
            selectedColor = this.textContent.trim();
        });
    });
});
</script>
@endpush 