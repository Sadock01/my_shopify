@extends('shop.layout')

@section('title', 'Panier')

@section('content')
    <div class="min-h-screen flex flex-col">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1">
            <!-- Header avec compteur -->
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Panier</h1>
                <div class="flex items-center space-x-4">
                    <!-- Compteur de produits -->
                    <div class="bg-primary text-white px-4 py-2 rounded-lg">
                        <span class="font-semibold">
                            <span id="total-items-count">0</span> 
                            <span id="items-text">article</span>
                        </span>
                    </div>
                    
                    <!-- Bouton vider le panier -->
                    <button onclick="clearCart()" id="clear-cart-btn" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors font-medium" style="display: none;">
                        Vider le panier
                    </button>
                </div>
            </div>
        
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
        console.log('=== loadCart() appelé ===');
        
        // Vérifier si l'utilisateur est connecté
        const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
        console.log('Utilisateur connecté:', isAuthenticated);
        
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
            console.log('Panier localStorage:', cart);
        }
        
        console.log('Panier final utilisé:', cart);
        
        // Si le panier a des produits, les afficher directement
        if (cart.length > 0) {
            console.log('=== VÉRIFICATION INFORMATIONS PRODUITS ===');
            console.log('Panier à vérifier:', cart);
            
            // Vérifier si les produits ont toutes les informations nécessaires
            const hasCompleteInfo = cart.every(item => item.name && item.price && item.image);
            console.log('Informations complètes:', hasCompleteInfo);
            
            if (!hasCompleteInfo) {
                console.log('=== INFORMATIONS MANQUANTES ===');
                cart.forEach((item, index) => {
                    console.log(`Produit ${index}:`, {
                        name: item.name,
                        price: item.price,
                        image: item.image,
                        hasName: !!item.name,
                        hasPrice: !!item.price,
                        hasImage: !!item.image
                    });
                });
            }
            
            if (hasCompleteInfo) {
                console.log('=== AFFICHAGE DIRECT ===');
                displayCartWithProducts(cart, null);
            } else {
                console.log('=== RECHERCHE PRODUITS VIA AJAX ===');
                const productIds = cart.map(item => item.product_id);
                console.log('IDs des produits à rechercher:', productIds);
                const productsRoute = '{{ $shop->isOnCustomDomain() ? route("shop.cart-products") : route("shop.cart-products.slug", $shop->slug) }}';
                console.log('Route produits:', productsRoute);
                
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
                .then(response => {
                    console.log('=== RÉPONSE AJAX PRODUITS ===');
                    console.log('Status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('=== DONNÉES PRODUITS REÇUES ===');
                    console.log('Success:', data.success);
                    console.log('Products:', data.products);
                    if (data.success) {
                        console.log('=== AFFICHAGE AVEC PRODUITS ===');
                        displayCartWithProducts(cart, data.products);
                    } else {
                        console.log('=== ÉCHEC, AFFICHAGE VIDE ===');
                        displayEmptyCart();
                    }
                })
                .catch(error => {
                    console.log('=== ERREUR AJAX PRODUITS ===');
                    console.error('Erreur:', error);
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
        
        // Mettre à jour le compteur
        updateCartCounter(0);
    }
        
    function displayCartWithProducts(cart, products) {
        console.log('=== displayCartWithProducts() appelé ===');
        console.log('Cart:', cart);
        console.log('Products:', products);
        
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
            console.log(`Article ${index}:`, item);
            
            let product;
            if (products && products[item.product_id]) {
                // Utiliser les produits récupérés via AJAX
                product = products[item.product_id];
                console.log(`Prix original (AJAX):`, product.price, typeof product.price);
                // Convertir le prix en nombre si c'est une chaîne
                product.price = parseFloat(product.price);
                console.log(`Prix converti (AJAX):`, product.price, typeof product.price);
            } else if (item.name && item.price && item.image) {
                // Utiliser les informations stockées directement dans le panier
                console.log(`Prix original (localStorage):`, item.price, typeof item.price);
                product = {
                    name: item.name,
                    price: parseFloat(item.price), // Convertir en nombre
                    image: item.image,
                    category: item.category ? { name: item.category } : null
                };
                console.log(`Prix converti (localStorage):`, product.price, typeof product.price);
            } else {
                return; // Skip si le produit n'existe plus
            }
            
            const itemTotal = product.price * item.quantity;
            total += itemTotal;
            
            console.log(`Produit ${index}:`, product, `Quantité: ${item.quantity}, Total: ${itemTotal}`);
            
            html += `
                <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6 hover:shadow-md transition-shadow">
                    <!-- Version mobile (petits écrans) -->
                    <div class="sm:hidden">
                        <div class="flex items-start space-x-4">
                            <!-- Image du produit -->
                            <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                <img src="/storage/${product.image}" alt="${product.name}" class="w-full h-full object-cover">
                            </div>
                            
                            <!-- Contenu principal -->
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 truncate">${product.name}</h3>
                                <p class="text-sm text-gray-600 truncate">${product.category ? product.category.name : 'Catégorie'}</p>
                                
                                <!-- Prix et contrôles -->
                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="changeQuantity(${index}, -1)" class="w-8 h-8 border border-gray-300 rounded-md flex items-center justify-center hover:bg-gray-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <span class="w-8 text-center font-medium">${item.quantity}</span>
                                        <button onclick="changeQuantity(${index}, 1)" class="w-8 h-8 border border-gray-300 rounded-md flex items-center justify-center hover:bg-gray-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Prix -->
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900">${itemTotal.toFixed(2)}€</p>
                                    </div>
                                </div>
                                
                                <!-- Bouton supprimer -->
                                <div class="mt-3">
                                    <button onclick="removeItem(${index})" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Version desktop (grands écrans) -->
                    <div class="hidden sm:block">
                        <div class="flex items-start space-x-6">
                            <!-- Image du produit -->
                            <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                <img src="/storage/${product.image}" alt="${product.name}" class="w-full h-full object-cover">
                            </div>
                            
                            <!-- Contenu principal -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-gray-900 text-lg mb-1">${product.name}</h3>
                                        <p class="text-sm text-gray-500 mb-3">${product.category ? product.category.name : 'Catégorie'}</p>
                                        
                                        <!-- Contrôles de quantité -->
                                        <div class="flex items-center space-x-3">
                                            <span class="text-sm text-gray-600 font-medium">Quantité:</span>
                                            <div class="flex items-center space-x-2">
                                                <button onclick="changeQuantity(${index}, -1)" class="w-9 h-9 border border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-50 hover:border-gray-400 transition-colors">
                                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                    </svg>
                                                </button>
                                                <span class="w-12 text-center font-semibold text-gray-900">${item.quantity}</span>
                                                <button onclick="changeQuantity(${index}, 1)" class="w-9 h-9 border border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-50 hover:border-gray-400 transition-colors">
                                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Prix et actions -->
                                    <div class="text-right ml-6">
                                        <div class="mb-3">
                                            <p class="text-2xl font-bold text-gray-900">${itemTotal.toFixed(2)}€</p>
                                            <p class="text-sm text-gray-500">${product.price.toFixed(2)}€ × ${item.quantity}</p>
                                        </div>
                                        
                                        <!-- Bouton supprimer -->
                                        <button onclick="removeItem(${index})" class="inline-flex items-center space-x-2 text-red-600 hover:text-red-700 hover:bg-red-50 px-3 py-2 rounded-lg transition-colors group">
                                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <span class="text-sm font-medium">Supprimer</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                <a href="{{ route('shop.checkout.delivery.slug', ['shop' => request()->route('shop')]) }}" 
                                   class="w-full bg-black text-white py-3 px-6 rounded-lg font-medium hover:bg-gray-800 transition-colors text-center block"
                                   onclick="console.log('=== CLIC SUR FINALISER LA COMMANDE ==='); console.log('Panier localStorage:', JSON.parse(localStorage.getItem('cart_{{ $shop->id }}') || '[]')); console.log('URL de destination:', this.href);">
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
        
        // Calculer le nombre total d'articles
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        
        // Mettre à jour le compteur
        updateCartCounter(totalItems);
    }
    
    // Fonction pour mettre à jour le compteur d'articles
    function updateCartCounter(count) {
        const countElement = document.getElementById('total-items-count');
        const textElement = document.getElementById('items-text');
        const clearBtn = document.getElementById('clear-cart-btn');
        
        if (countElement && textElement) {
            countElement.textContent = count;
            
            // Gérer le pluriel
            if (count <= 1) {
                textElement.textContent = 'article';
            } else {
                textElement.textContent = 'articles';
            }
            
            // Afficher/masquer le bouton "Vider le panier"
            if (clearBtn) {
                if (count > 0) {
                    clearBtn.style.display = 'block';
                } else {
                    clearBtn.style.display = 'none';
                }
            }
        }
    }
    
    function changeQuantity(index, delta) {
        console.log(`=== changeQuantity(${index}, ${delta}) ===`);
        
        const cart = JSON.parse(localStorage.getItem('cart_{{ $shop->id }}') || '[]');
        console.log('Panier avant modification:', cart);
        
        if (cart[index]) {
            const newQuantity = parseInt(cart[index].quantity) + delta;
            console.log(`Nouvelle quantité pour l'article ${index}: ${newQuantity}`);
            
            if (newQuantity <= 0) {
                // Supprimer l'article si la quantité devient 0 ou négative
                cart.splice(index, 1);
                console.log('Article supprimé du panier');
            } else {
                // Mettre à jour la quantité
                cart[index].quantity = newQuantity;
                console.log('Quantité mise à jour:', cart[index]);
            }
            
            // Sauvegarder dans localStorage
            localStorage.setItem('cart_{{ $shop->id }}', JSON.stringify(cart));
            console.log('Panier sauvegardé:', cart);
            
            // Synchroniser avec la session si l'utilisateur est connecté
            const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
            if (isAuthenticated) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    const syncRoute = '{{ $shop->isOnCustomDomain() ? route("shop.cart-sync") : route("shop.cart-sync.slug", $shop->slug) }}';
                    fetch(syncRoute, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                        },
                        body: JSON.stringify({ cart: cart })
                    }).catch(error => {
                        console.log('Erreur lors de la synchronisation:', error);
                    });
                }
            }
            
            // Forcer le rechargement immédiat
            setTimeout(() => {
                loadCart();
                updateCartCount();
            }, 100);
            
            console.log('Rechargement du panier déclenché');
        } else {
            console.log('Article non trouvé à l\'index:', index);
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
        const count = cart.reduce((total, item) => total + parseInt(item.quantity), 0);
        
        // Mettre à jour le compteur du header (mobile)
        const cartBadge = document.getElementById('cart-count-{{ $shop->id }}');
        const cartBadgeMobile = document.getElementById('cart-count-mobile-{{ $shop->id }}');
        
        if (cartBadge) {
            cartBadge.textContent = count;
            cartBadge.style.display = count > 0 ? 'flex' : 'none';
        }
        
        if (cartBadgeMobile) {
            cartBadgeMobile.textContent = count;
            cartBadgeMobile.style.display = count > 0 ? 'flex' : 'none';
        }
        
        // Mettre à jour le compteur de la page panier
        updateCartCounter(count);
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
        console.log('=== CHARGEMENT PAGE PANIER ===');
        console.log('Shop ID:', '{{ $shop->id }}');
        console.log('Shop Slug:', '{{ $shop->slug }}');
        
        // Vérifier si on est déjà en train de synchroniser
        const syncKey = 'syncing_cart_{{ $shop->id }}';
        if (sessionStorage.getItem(syncKey)) {
            console.log('=== SYNCHRONISATION DÉJÀ EN COURS ===');
            loadCart();
            updateCartCount();
            return;
        }
        
        // Synchroniser automatiquement le localStorage vers la session pour les utilisateurs connectés
        const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
        console.log('Utilisateur connecté:', isAuthenticated);
        
        if (isAuthenticated) {
            const localStorageCart = JSON.parse(localStorage.getItem('cart_{{ $shop->id }}') || '[]');
            const sessionCart = @json($cart);
            
            console.log('=== ÉTAT DES PANIERS ===');
            console.log('localStorage cart:', localStorageCart);
            console.log('localStorage cart length:', localStorageCart.length);
            console.log('Session cart:', sessionCart);
            console.log('Session cart length:', sessionCart.length);
            
            // Si localStorage a des produits mais pas la session, synchroniser directement
            if (localStorageCart.length > 0 && sessionCart.length === 0) {
                console.log('=== SYNCHRONISATION NÉCESSAIRE ===');
                console.log('localStorage a', localStorageCart.length, 'articles, session a', sessionCart.length);
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                console.log('CSRF Token trouvé:', !!csrfToken);
                
                if (csrfToken) {
                    console.log('=== ENVOI REQUÊTE AJAX ===');
                    console.log('URL:', '/force-cart-sync');
                    console.log('Données à envoyer:', JSON.stringify(localStorageCart));
                    
                    // Marquer comme en cours de synchronisation
                    sessionStorage.setItem(syncKey, 'true');
                    
                    // Utiliser la route de synchronisation forcée
                    fetch('/force-cart-sync', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ cart_data: JSON.stringify(localStorageCart) })
                    })
                    .then(response => {
                        console.log('=== RÉPONSE REÇUE ===');
                        console.log('Status:', response.status);
                        console.log('OK:', response.ok);
                        return response.json();
                    })
                    .then(data => {
                        console.log('=== DONNÉES RÉPONSE ===');
                        console.log('Panier synchronisé via force-cart-sync:', data);
                        console.log('=== SYNCHRONISATION TERMINÉE ===');
                        // Supprimer le flag de synchronisation
                        sessionStorage.removeItem(syncKey);
                        // Ne pas recharger la page, juste charger le panier
                        loadCart();
                        updateCartCount();
                    })
                    .catch(error => {
                        console.log('=== ERREUR AJAX ===');
                        console.error('Erreur de synchronisation:', error);
                        // Supprimer le flag de synchronisation même en cas d'erreur
                        sessionStorage.removeItem(syncKey);
                        // Charger le panier même en cas d'erreur
                        loadCart();
                        updateCartCount();
                    });
                } else {
                    console.log('=== CSRF TOKEN MANQUANT ===');
                    loadCart();
                    updateCartCount();
                }
            } else {
                console.log('=== AUCUNE SYNCHRONISATION NÉCESSAIRE ===');
                console.log('localStorage:', localStorageCart.length, 'articles');
                console.log('Session:', sessionCart.length, 'articles');
                loadCart();
                updateCartCount();
            }
        } else {
            console.log('=== UTILISATEUR NON CONNECTÉ ===');
            loadCart();
            updateCartCount();
        }
    });
</script>
@endpush 