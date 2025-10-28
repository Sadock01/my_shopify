@extends('shop.layout')

@section('title', 'Informations de livraison')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-8">
        <a href="{{ route('shop.home.slug', ['shop' => $shop->slug]) }}" class="hover:text-primary">Accueil</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <a href="/shop/{{ $shop->slug }}/cart" class="hover:text-primary">Panier</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span class="text-gray-900 font-medium">Informations de livraison</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulaire de livraison -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Informations de livraison</h1>
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                <form id="delivery-form" action="{{ route('shop.checkout.process.slug', ['shop' => $shop->slug]) }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="cart_data" id="cart-data-input" value="">
                    
                    <!-- Informations personnelles -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Informations personnelles</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                                <input type="text" 
                                       id="first_name" 
                                       name="first_name" 
                                       value="{{ old('first_name', auth()->user()->name ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                       required>
                                @error('first_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                                <input type="text" 
                                       id="last_name" 
                                       name="last_name" 
                                       value="{{ old('last_name') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                       required>
                                @error('last_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', auth()->user()->email ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone *</label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required>
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Adresse de livraison -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Adresse de livraison</h3>
                        
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Adresse complète *</label>
                            <textarea id="address" 
                                      name="address" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                      placeholder="Numéro, rue, appartement..."
                                      required>{{ old('address') }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Ville *</label>
                                <input type="text" 
                                       id="city" 
                                       name="city" 
                                       value="{{ old('city') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                       required>
                                @error('city')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Code postal *</label>
                                <input type="text" 
                                       id="postal_code" 
                                       name="postal_code" 
                                       value="{{ old('postal_code') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                       required>
                                @error('postal_code')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Pays *</label>
                                <select id="country" 
                                        name="country" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                        required>
                                    <option value="">Sélectionner un pays</option>
                                    <option value="France" {{ old('country') == 'France' ? 'selected' : '' }}>France</option>
                                    <option value="Belgique" {{ old('country') == 'Belgique' ? 'selected' : '' }}>Belgique</option>
                                    <option value="Suisse" {{ old('country') == 'Suisse' ? 'selected' : '' }}>Suisse</option>
                                    <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                                    <option value="Autre" {{ old('country') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('country')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Instructions spéciales -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Instructions de livraison</h3>
                        
                        <div>
                            <label for="delivery_instructions" class="block text-sm font-medium text-gray-700 mb-2">Instructions spéciales (optionnel)</label>
                            <textarea id="delivery_instructions" 
                                      name="delivery_instructions" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                      placeholder="Ex: Sonner à la porte, laisser chez le voisin, etc.">{{ old('delivery_instructions') }}</textarea>
                            @error('delivery_instructions')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                        <a href="/shop/{{ $shop->slug }}/cart" 
                           class="flex-1 bg-gray-100 text-gray-900 py-3 px-6 rounded-md font-semibold hover:bg-gray-200 transition-colors text-center">
                            ← Retour au panier
                        </a>
                        <button type="submit" 
                                class="flex-1 bg-primary text-white py-3 px-6 rounded-md font-semibold hover:opacity-90 transition-opacity">
                            Continuer vers le paiement →
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Résumé de la commande -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Résumé de votre commande</h2>
                
                <!-- Articles du panier -->
                <div class="space-y-4 mb-6">
                    @if($cart && count($cart) > 0)
                        @foreach($cart as $item)
                        <div class="flex items-center space-x-3">
                            @if(isset($item['image']) && $item['image'])
                                <img src="{{ Storage::url($item['image']) }}" 
                                     alt="{{ $item['name'] }}" 
                                     class="w-12 h-12 object-cover rounded-md">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-md flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">{{ $item['name'] ?? 'Produit' }}</h4>
                                <p class="text-sm text-gray-500">Quantité: {{ $item['quantity'] ?? 1 }}</p>
                            </div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }} €
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>

                <!-- Total -->
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between items-center text-lg font-bold text-gray-900">
                        <span>Total</span>
                        <span>{{ number_format(array_sum(array_map(function($item) { return ($item['price'] ?? 0) * ($item['quantity'] ?? 1); }, $cart ?? [])), 2) }} €</span>
                    </div>
                </div>

                <!-- Informations de sécurité -->
                <div class="mt-6 p-4 bg-green-50 rounded-lg">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span class="text-sm text-green-700 font-medium">Paiement sécurisé</span>
                    </div>
                    <p class="text-xs text-green-600 mt-1">Vos informations sont protégées et chiffrées</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== PAGE DELIVERY CHARGÉE ===');
    console.log('Shop ID:', '{{ $shop->id }}');
    console.log('Shop Slug:', '{{ $shop->slug }}');
    
    // Vérifier le panier localStorage
    const localStorageCart = JSON.parse(localStorage.getItem('cart_{{ $shop->id }}') || '[]');
    console.log('Panier localStorage:', localStorageCart);
    console.log('Nombre d\'articles dans localStorage:', localStorageCart.length);
    
    // Vérifier le panier session (passé depuis le contrôleur)
    const sessionCart = @json($cart);
    console.log('Panier session:', sessionCart);
    console.log('Nombre d\'articles dans session:', sessionCart.length);
    
    const form = document.getElementById('delivery-form');
    console.log('Formulaire trouvé:', !!form);
    console.log('Action du formulaire:', form ? form.action : 'N/A');
    
    if (form) {
        form.addEventListener('submit', function(event) {
            console.log('=== SOUMISSION DU FORMULAIRE ===');
            
            // Récupérer le panier depuis localStorage
            const localStorageCart = JSON.parse(localStorage.getItem('cart_{{ $shop->id }}') || '[]');
            console.log('Panier localStorage au moment de la soumission:', localStorageCart);
            
            if (localStorageCart.length === 0) {
                console.error('ERREUR: Panier localStorage vide');
                event.preventDefault();
                alert('Votre panier est vide. Veuillez ajouter des produits avant de continuer.');
                return;
            }
            
            // Mettre à jour le champ caché avec les données du panier
            const cartDataInput = document.getElementById('cart-data-input');
            if (cartDataInput) {
                cartDataInput.value = JSON.stringify(localStorageCart);
                console.log('Données du panier ajoutées au formulaire:', JSON.stringify(localStorageCart));
            }
            
            // Vider le panier localStorage immédiatement après soumission
            localStorage.removeItem('cart_{{ $shop->id }}');
            console.log('Panier localStorage vidé après soumission');
            
            // Mettre à jour le compteur de panier
            if (window.updateCartCounters) {
                window.updateCartCounters();
            }
            
            console.log('=== SOUMISSION DU FORMULAIRE ===');
            console.log('URL de destination:', form.action);
            console.log('Méthode:', form.method);
            console.log('Formulaire soumis normalement !');
        });
    }
});
</script>
@endpush
@endsection
