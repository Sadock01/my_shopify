@extends('shop.layout')

@section('title', 'Panier')

@section('content')
    <div class="min-h-screen flex flex-col">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Panier</h1>
        
        @auth
            <!-- Utilisateur connecté - afficher le panier de la session -->
            <div id="cart-container">
                <!-- Cart will be loaded here via JavaScript -->
            </div>
        @else
            <!-- Utilisateur non connecté - afficher le panier du localStorage -->
        <div id="cart-container">
            <!-- Cart will be loaded here via JavaScript -->
        </div>
        @endauth
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function loadCart() {
        // Vérifier si l'utilisateur est connecté
        const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
        
        let cart;
        if (isAuthenticated) {
            // Utilisateur connecté - synchroniser localStorage vers session
            const localStorageCart = JSON.parse(localStorage.getItem('cart_{{ $shop->id }}') || '[]');
            const sessionCart = @json($cart);
            
            // Si le localStorage a des produits mais pas la session, synchroniser
            if (localStorageCart.length > 0 && sessionCart.length === 0) {
                cart = localStorageCart;
                
                // Essayer de synchroniser le localStorage vers la session (optionnel)
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    const syncRoute = '{{ $shop->isOnCustomDomain() ? route("shop.cart-sync") : route("shop.cart-sync.slug", $shop->slug) }}';
                    fetch(syncRoute, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                        },
                        body: JSON.stringify({ cart: localStorageCart })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Panier synchronisé
                    })
                    .catch(error => {
                        // Erreur lors de la synchronisation
                    });
                }
            } else {
                cart = sessionCart;
            }
        } else {
            // Utilisateur non connecté - utiliser le localStorage
            cart = JSON.parse(localStorage.getItem('cart_{{ $shop->id }}') || '[]');
        }
        
        // Si le panier a des produits, les afficher directement
        if (cart.length > 0) {
            // Vérifier si les produits ont toutes les informations nécessaires
            const hasCompleteInfo = cart.every(item => item.name && item.price && item.image);
            
            if (hasCompleteInfo) {
                displayCartWithProducts(cart, null);
            } else {
                const productIds = cart.map(item => item.product_id);
                const productsRoute = '{{ $shop->isOnCustomDomain() ? route("shop.cart-products") : route("shop.cart-products.slug", $shop->slug) }}';
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                const headers = {
                    'Content-Type': 'application/json'
                };
                
                if (csrfToken) {
                    headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
                }
                
                fetch(productsRoute, {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify({ product_ids: productIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayCartWithProducts(cart, data.products);
                    } else {
                        displayEmptyCart();
                    }
                })
                .catch(error => {
                    displayEmptyCart();
                });
            }
        } else {
            displayEmptyCart();
        }
    }
    
    function displayEmptyCart() {
        const container = document.getElementById('cart-container');
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
        }
        
    function displayCartWithProducts(cart, products) {
        
        const container = document.getElementById('cart-container');
        let total = 0;
        let html = `
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-semibold">Articles du panier</h2>
                                <button onclick="clearCart()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                                    Vider le panier
                                </button>
                            </div>
                            <div class="space-y-4">
        `;
        
        cart.forEach((item, index) => {
            let product;
            if (products && products[item.product_id]) {
                // Utiliser les produits récupérés via AJAX
                product = products[item.product_id];
            } else if (item.name && item.price && item.image) {
                // Utiliser les informations stockées directement dans le panier
                product = {
                    name: item.name,
                    price: item.price,
                    image: item.image,
                    category: item.category ? { name: item.category } : null
                };
            } else {
                return; // Skip si le produit n'existe plus
            }
            
            const itemTotal = product.price * item.quantity;
            total += itemTotal;
            
            html += `
                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                    <img src="/storage/${product.image}" alt="${product.name}" class="w-20 h-20 object-cover rounded-md">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">${product.name}</h3>
                        <p class="text-sm text-gray-600">${product.category ? product.category.name : 'Catégorie'}</p>
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
                        
                        <!-- Bouton de commande -->
                        <div class="space-y-4">
                            @auth
                                <a href="{{ route('shop.payment-info.slug', ['shop' => request()->route('shop')]) }}" class="w-full bg-black text-white py-3 px-6 rounded-lg font-medium hover:bg-gray-800 transition-colors text-center block">
                                    Finaliser la commande
                                </a>
                            @else
                            <a href="{{ route('shop.login.slug', ['shop' => request()->route('shop')]) }}" class="w-full bg-black text-white py-3 px-6 rounded-lg font-medium hover:bg-gray-800 transition-colors text-center block">
                                Se connecter pour commander
                            </a>
                            @endauth
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
            cart[index].quantity = parseInt(cart[index].quantity) + delta;
            
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
        // Fonction temporairement désactivée - compteur masqué
        console.log('updateCartCount appelé mais désactivé (compteur masqué)');
        return;
        
        const cart = JSON.parse(localStorage.getItem('cart_{{ $shop->id }}') || '[]');
        const count = cart.reduce((total, item) => total + parseInt(item.quantity), 0);
        
        const cartBadge = document.getElementById('cart-count-{{ $shop->id }}');
        if (cartBadge) {
            cartBadge.textContent = count;
            cartBadge.style.display = count > 0 ? 'flex' : 'none';
        }
    }
    
    function clearCart() {
        if (confirm('Êtes-vous sûr de vouloir vider votre panier ?')) {
            // Vider le localStorage
            localStorage.removeItem('cart_{{ $shop->id }}');
            
            // Vider la session si l'utilisateur est connecté
            const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
            if (isAuthenticated) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    const clearRoute = '{{ $shop->isOnCustomDomain() ? route("shop.cart-sync") : route("shop.cart-sync.slug", $shop->slug) }}';
                    fetch(clearRoute, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                        },
                        body: JSON.stringify({ cart: [] })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Panier vidé dans la session
                    })
                    .catch(error => {
                        // Erreur lors du vidage de la session
                    });
                }
            }
            
            // Recharger le panier et mettre à jour le compteur
            loadCart();
            updateCartCount();
            
            console.log('Panier vidé avec succès');
        }
    }
    
    
    
    // Load cart on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadCart();
    });
</script>
@endpush 