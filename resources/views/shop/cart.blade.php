@extends('shop.layout')

@section('title', 'Panier')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Panier</h1>
        
        <div id="cart-container">
            <!-- Cart will be loaded here via JavaScript -->
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function loadCart() {
        const cart = JSON.parse(localStorage.getItem('cart_{{ $shop->id }}') || '[]');
        const container = document.getElementById('cart-container');
        
        if (cart.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Votre panier est vide</h3>
                    <p class="text-gray-500 mb-6">Ajoutez des produits pour commencer vos achats.</p>
                    <a href="{{ $shop->isOnCustomDomain() ? route('shop.products') : route('shop.products.slug', $shop->slug) }}" class="inline-block bg-primary text-white px-6 py-3 rounded-md font-semibold hover:opacity-90 transition-opacity">
                        Continuer les achats
                    </a>
                </div>
            `;
            return;
        }
        
        let total = 0;
        let html = `
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold mb-6">Articles du panier</h2>
                            <div class="space-y-4">
        `;
        
        cart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            
            html += `
                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                    <img src="${item.image}" alt="${item.name}" class="w-20 h-20 object-cover rounded-md">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">${item.name}</h3>
                        <p class="text-sm text-gray-600">
                            ${item.size ? 'Taille: ' + item.size : ''}
                            ${item.color ? 'Couleur: ' + item.color : ''}
                        </p>
                        <div class="flex items-center space-x-2 mt-2">
                            <button onclick="changeQuantity(${index}, -1)" class="w-8 h-8 border border-gray-300 rounded-md flex items-center justify-center hover:bg-gray-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <span class="w-12 text-center">${item.quantity}</span>
                            <button onclick="changeQuantity(${index}, 1)" class="w-8 h-8 border border-gray-300 rounded-md flex items-center justify-center hover:bg-gray-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">${itemTotal.toFixed(2)}€</p>
                        <button onclick="removeItem(${index})" class="text-red-600 hover:text-red-800 text-sm mt-2">
                            Supprimer
                        </button>
                    </div>
                </div>
            `;
        });
        
        html += `
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold mb-6">Résumé de la commande</h2>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Sous-total</span>
                                <span class="font-semibold">${total.toFixed(2)}€</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Livraison</span>
                                <span class="font-semibold">Gratuit</span>
                            </div>
                            <hr class="border-gray-200">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total</span>
                                <span>${total.toFixed(2)}€</span>
                            </div>
                        </div>
                        
                        <button onclick="proceedToCheckout()" class="w-full bg-primary text-white py-3 px-6 rounded-md font-semibold hover:opacity-90 transition-opacity">
                            Passer la commande
                        </button>
                        
                        <div class="mt-4 text-center">
                            <a href="{{ $shop->isOnCustomDomain() ? route('shop.products') : route('shop.products.slug', $shop->slug) }}" class="text-primary hover:underline">
                                Continuer les achats
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        container.innerHTML = html;
    }
    
    function changeQuantity(index, delta) {
        const cart = JSON.parse(localStorage.getItem('cart_{{ $shop->id }}') || '[]');
        const newQuantity = cart[index].quantity + delta;
        
        if (newQuantity > 0) {
            cart[index].quantity = newQuantity;
            localStorage.setItem('cart_{{ $shop->id }}', JSON.stringify(cart));
            loadCart();
            updateCartCount();
        }
    }
    
    function removeItem(index) {
        const cart = JSON.parse(localStorage.getItem('cart_{{ $shop->id }}') || '[]');
        cart.splice(index, 1);
        localStorage.setItem('cart_{{ $shop->id }}', JSON.stringify(cart));
        loadCart();
        updateCartCount();
    }
    
    function proceedToCheckout() {
        const cart = JSON.parse(localStorage.getItem('cart_{{ $shop->id }}') || '[]');
        if (cart.length === 0) {
            alert('Votre panier est vide');
            return;
        }
        
        // Redirect to checkout page
        window.location.href = '{{ $shop->isOnCustomDomain() ? route("shop.checkout") : route("shop.checkout.slug", $shop->slug) }}';
    }
    
    // Load cart on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadCart();
    });
</script>
@endpush 