@extends('shop.layout')

@section('title', $product->name)

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Images -->
            <div class="space-y-4">
                <!-- Main Image -->
                <div class="bg-gray-100 rounded-lg overflow-hidden">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-96 object-cover" id="main-image">
                </div>
                
                <!-- Thumbnail Images -->
                @if($product->images && count($product->images) > 0)
                <div class="grid grid-cols-4 gap-4">
                    <div class="bg-gray-100 rounded-lg overflow-hidden cursor-pointer border-2 border-primary" onclick="changeMainImage('{{ $product->image }}')">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-24 object-cover">
                    </div>
                    @foreach($product->images as $image)
                        <div class="bg-gray-100 rounded-lg overflow-hidden cursor-pointer hover:border-2 hover:border-primary" onclick="changeMainImage('{{ $image }}')">
                            <img src="{{ $image }}" alt="{{ $product->name }}" class="w-full h-24 object-cover">
                        </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Product Details -->
            <div class="space-y-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                    <div class="flex items-center space-x-4">
                        @if($product->original_price && $product->original_price > $product->price)
                            <span class="text-2xl text-gray-500 line-through">{{ number_format($product->original_price, 2) }}€</span>
                        @endif
                        <span class="text-3xl font-bold text-primary">{{ number_format($product->price, 2) }}€</span>
                        @if($product->original_price && $product->original_price > $product->price)
                            <span class="bg-red-500 text-white px-2 py-1 rounded text-sm font-medium">
                                -{{ round((($product->original_price - $product->price) / $product->original_price) * 100) }}%
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <h3 class="text-lg font-semibold mb-2">Description</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                </div>

                <!-- Size Selection -->
                @if($product->sizes && count($product->sizes) > 0)
                <div>
                    <h3 class="text-lg font-semibold mb-3">Taille</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->sizes as $size)
                            <button onclick="selectSize('{{ $size }}')" class="size-btn px-4 py-2 border border-gray-300 rounded-md hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary" data-size="{{ $size }}">
                                {{ $size }}
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Color Selection -->
                @if($product->colors && count($product->colors) > 0)
                <div>
                    <h3 class="text-lg font-semibold mb-3">Couleur</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->colors as $color)
                            <button onclick="selectColor('{{ $color }}')" class="color-btn px-4 py-2 border border-gray-300 rounded-md hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary" data-color="{{ $color }}">
                                {{ $color }}
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Quantity -->
                <div>
                    <h3 class="text-lg font-semibold mb-3">Quantité</h3>
                    <div class="flex items-center space-x-4">
                        <button onclick="changeQuantity(-1)" class="w-10 h-10 border border-gray-300 rounded-md flex items-center justify-center hover:bg-gray-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-16 h-10 border border-gray-300 rounded-md text-center focus:outline-none focus:ring-2 focus:ring-primary">
                        <button onclick="changeQuantity(1)" class="w-10 h-10 border border-gray-300 rounded-md flex items-center justify-center hover:bg-gray-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </div>
                    @if($product->stock > 0)
                        <p class="text-sm text-gray-600 mt-2">{{ $product->stock }} en stock</p>
                    @else
                        <p class="text-sm text-red-600 mt-2">Rupture de stock</p>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="space-y-4">
                    @if($product->stock > 0)
                        <button onclick="addToCartWithOptions()" class="w-full bg-primary text-white py-3 px-6 rounded-md font-semibold hover:opacity-90 transition-opacity flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <span>Ajouter au panier</span>
                        </button>
                    @else
                        <button disabled class="w-full bg-gray-300 text-gray-500 py-3 px-6 rounded-md font-semibold cursor-not-allowed">
                            Rupture de stock
                        </button>
                    @endif
                    
                    <button class="w-full bg-purple-600 text-white py-3 px-6 rounded-md font-semibold hover:opacity-90 transition-opacity flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span>Acheter avec Shop Pay</span>
                    </button>
                    
                    <p class="text-sm text-gray-600 text-center">Plus d'options de paiement</p>
                </div>

                <!-- Shop Info -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">
                        Ceci est une boutique de démonstration. Vous pouvez acheter des produits similaires chez {{ $shop->name }}.
                    </p>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts && count($relatedProducts) > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Produits similaires</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="group">
                        <div class="relative overflow-hidden bg-gray-100 rounded-lg mb-4">
                            <img src="{{ $relatedProduct->image }}" alt="{{ $relatedProduct->name }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $relatedProduct->name }}</h3>
                            <div class="flex justify-center items-center space-x-2">
                                @if($relatedProduct->original_price && $relatedProduct->original_price > $relatedProduct->price)
                                    <span class="text-gray-500 line-through text-sm">{{ number_format($relatedProduct->original_price, 2) }}€</span>
                                @endif
                                <span class="text-xl font-bold text-primary">{{ number_format($relatedProduct->price, 2) }}€</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    let selectedSize = null;
    let selectedColor = null;

    function changeMainImage(src) {
        document.getElementById('main-image').src = src;
        
        // Update active thumbnail
        document.querySelectorAll('[onclick^="changeMainImage"]').forEach(btn => {
            btn.classList.remove('border-primary');
            btn.classList.add('border-gray-300');
        });
        event.target.parentElement.classList.remove('border-gray-300');
        event.target.parentElement.classList.add('border-primary');
    }

    function selectSize(size) {
        selectedSize = size;
        
        // Update active button
        document.querySelectorAll('.size-btn').forEach(btn => {
            btn.classList.remove('border-primary', 'bg-primary', 'text-white');
            btn.classList.add('border-gray-300');
        });
        event.target.classList.remove('border-gray-300');
        event.target.classList.add('border-primary', 'bg-primary', 'text-white');
    }

    function selectColor(color) {
        selectedColor = color;
        
        // Update active button
        document.querySelectorAll('.color-btn').forEach(btn => {
            btn.classList.remove('border-primary', 'bg-primary', 'text-white');
            btn.classList.add('border-gray-300');
        });
        event.target.classList.remove('border-gray-300');
        event.target.classList.add('border-primary', 'bg-primary', 'text-white');
    }

    function changeQuantity(delta) {
        const input = document.getElementById('quantity');
        const newValue = parseInt(input.value) + delta;
        const max = parseInt(input.getAttribute('max'));
        
        if (newValue >= 1 && newValue <= max) {
            input.value = newValue;
        }
    }

    function addToCartWithOptions() {
        const quantity = parseInt(document.getElementById('quantity').value);
        
        // Validate selections
        const sizes = @json($product->sizes ?? []);
        const colors = @json($product->colors ?? []);
        
        if (sizes.length > 0 && !selectedSize) {
            alert('Veuillez sélectionner une taille');
            return;
        }
        
        if (colors.length > 0 && !selectedColor) {
            alert('Veuillez sélectionner une couleur');
            return;
        }
        
        // Add to cart with options
        const cartItem = {
            product_id: {{ $product->id }},
            quantity: quantity,
            size: selectedSize,
            color: selectedColor,
            price: {{ $product->price }},
            name: '{{ $product->name }}',
            image: '{{ $product->image }}'
        };
        
        const cart = JSON.parse(localStorage.getItem('cart_{{ $shop->id }}') || '[]');
        cart.push(cartItem);
        localStorage.setItem('cart_{{ $shop->id }}', JSON.stringify(cart));
        
        updateCartCount();
        alert('Produit ajouté au panier !');
    }
</script>
@endpush 