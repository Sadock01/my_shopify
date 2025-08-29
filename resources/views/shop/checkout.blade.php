@extends('shop.layout')

@section('title', 'Finaliser la commande')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Finaliser la commande</h1>
        
        <form action="{{ route('shop.checkout') }}" method="POST" id="checkout-form">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Customer Information -->
                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Informations personnelles</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">Nom complet *</label>
                                <input type="text" id="customer_name" name="customer_name" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            
                            <div>
                                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" id="customer_email" name="customer_email" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            
                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                                <input type="tel" id="customer_phone" name="customer_phone" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            
                            <div>
                                <label for="customer_address" class="block text-sm font-medium text-gray-700 mb-2">Adresse de livraison</label>
                                <textarea id="customer_address" name="customer_address" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Notes de commande</h2>
                        
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Instructions spéciales (optionnel)</label>
                            <textarea id="notes" name="notes" rows="3" placeholder="Instructions de livraison, préférences, etc." class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Résumé de la commande</h2>
                        
                        <div id="order-items" class="space-y-4 mb-6">
                            <!-- Order items will be loaded here -->
                        </div>
                        
                        <hr class="border-gray-200 mb-4">
                        
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Sous-total</span>
                                <span id="subtotal" class="font-semibold">0.00€</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Livraison</span>
                                <span class="font-semibold">Gratuit</span>
                            </div>
                            <hr class="border-gray-200">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total</span>
                                <span id="total" class="text-primary">0.00€</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">Informations de paiement</h3>
                        <p class="text-blue-800 mb-4">
                            Après avoir soumis votre commande, vous recevrez les informations bancaires pour effectuer le virement.
                        </p>
                        <ul class="space-y-2 text-blue-800 text-sm">
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Paiement sécurisé par virement bancaire</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Aucune donnée bancaire stockée</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Confirmation par email</span>
                            </li>
                        </ul>
                    </div>
                    
                    <button type="submit" class="w-full bg-primary text-white py-3 px-6 rounded-md font-semibold hover:opacity-90 transition-opacity">
                        Confirmer la commande
                    </button>
                    
                    <div class="text-center">
                        <a href="{{ route('shop.cart') }}" class="text-primary hover:underline">
                            ← Retour au panier
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    function loadOrderSummary() {
        const cart = JSON.parse(localStorage.getItem('cart_{{ $shop->id }}') || '[]');
        const container = document.getElementById('order-items');
        const subtotalEl = document.getElementById('subtotal');
        const totalEl = document.getElementById('total');
        
        if (cart.length === 0) {
            window.location.href = '{{ route("shop.cart") }}';
            return;
        }
        
        let subtotal = 0;
        let html = '';
        
        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            
            html += `
                <div class="flex items-center space-x-4">
                    <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded-md">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">${item.name}</h4>
                        <p class="text-sm text-gray-600">
                            Quantité: ${item.quantity}
                            ${item.size ? ' | Taille: ' + item.size : ''}
                            ${item.color ? ' | Couleur: ' + item.color : ''}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">${itemTotal.toFixed(2)}€</p>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
        subtotalEl.textContent = subtotal.toFixed(2) + '€';
        totalEl.textContent = subtotal.toFixed(2) + '€';
        
        // Add hidden inputs for form submission
        const form = document.getElementById('checkout-form');
        
        // Remove existing hidden inputs
        document.querySelectorAll('input[name="items"]').forEach(input => input.remove());
        document.querySelectorAll('input[name="total_amount"]').forEach(input => input.remove());
        
        // Add new hidden inputs
        const itemsInput = document.createElement('input');
        itemsInput.type = 'hidden';
        itemsInput.name = 'items';
        itemsInput.value = JSON.stringify(cart);
        form.appendChild(itemsInput);
        
        const totalInput = document.createElement('input');
        totalInput.type = 'hidden';
        totalInput.name = 'total_amount';
        totalInput.value = subtotal;
        form.appendChild(totalInput);
    }
    
    // Load order summary on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadOrderSummary();
    });
</script>
@endpush 