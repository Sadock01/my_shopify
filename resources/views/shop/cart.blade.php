@extends('shop.layout')

@section('title', 'Panier')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Panier</h1>
        
        <!-- Message d'authentification -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-blue-800">Authentification requise</h3>
                    <div class="mt-2 text-blue-700">
                        <p>Vous devez être connecté pour finaliser votre commande. Cela nous permet de :</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Sauvegarder vos informations de livraison</li>
                            <li>Suivre vos commandes</li>
                            <li>Vous offrir un service client personnalisé</li>
                            <li>Garantir la sécurité de vos paiements</li>
                        </ul>
                    </div>
                    <div class="mt-4 flex space-x-4">
                        <a href="{{ route('shop.login.slug', ['shop' => request()->route('shop')]) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md font-medium hover:bg-blue-700 transition-colors">
                            Se connecter
                        </a>
                        <a href="{{ route('shop.register.slug', ['shop' => request()->route('shop')]) }}" class="bg-white text-blue-600 border border-blue-600 px-4 py-2 rounded-md font-medium hover:bg-blue-50 transition-colors">
                            Créer un compte
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
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
                    <a href="{{ $shop->isOnCustomDomain() ? route('shop.products') : route('shop.products.slug', $shop->slug) }}" class="inline-block bg-black text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-800 transition-colors">
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
                                <span class="font-semibold">Gratuite</span>
                            </div>
                            <hr class="border-gray-200">
                            <div class="flex justify-between text-lg font-semibold">
                                <span>Total</span>
                                <span>${total.toFixed(2)}€</span>
                            </div>
                        </div>
                        
                        <!-- Bouton de commande avec message d'authentification -->
                        <div class="space-y-4">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <span class="ml-2 text-sm text-yellow-800">Connexion requise pour commander</span>
                                </div>
                            </div>
                            
                            <a href="{{ route('shop.login.slug', ['shop' => request()->route('shop')]) }}" class="w-full bg-black text-white py-3 px-6 rounded-lg font-medium hover:bg-gray-800 transition-colors text-center block">
                                Se connecter pour commander
                            </a>
                            
                            <a href="{{ route('shop.register.slug', ['shop' => request()->route('shop')]) }}" class="w-full bg-white text-black border border-gray-300 py-3 px-6 rounded-lg font-medium hover:bg-gray-50 transition-colors text-center block">
                                Créer un compte
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
        
        if (cart[index]) {
            cart[index].quantity += delta;
            
            if (cart[index].quantity <= 0) {
                cart.splice(index, 1);
            }
            
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
    
    function updateCartCount() {
        const cart = JSON.parse(localStorage.getItem('cart_{{ $shop->id }}') || '[]');
        const count = cart.reduce((total, item) => total + item.quantity, 0);
        
        const cartBadge = document.querySelector('.cart-count');
        if (cartBadge) {
            cartBadge.textContent = count;
            cartBadge.style.display = count > 0 ? 'flex' : 'none';
        }
    }
    
    // Load cart on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadCart();
    });
</script>
@endpush 