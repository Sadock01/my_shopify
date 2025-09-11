<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyShopify - Démonstration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">MyShopify</h1>
                    <p class="text-xl text-gray-600">Démonstration des boutiques</p>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Boutiques disponibles</h2>
                <p class="text-lg text-gray-600">Cliquez sur une boutique pour voir l'interface client</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($shops as $shop)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                            @if(is_object($shop) && $shop->banner_image)
                                <img src="{{ $shop->banner_image }}" alt="{{ $shop->name }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                    <span class="text-white text-2xl font-bold">{{ is_object($shop) ? $shop->name : $shop }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ is_object($shop) ? $shop->name : $shop }}</h3>
                            <p class="text-gray-600 mb-4">{{ is_object($shop) ? $shop->description : 'Description non disponible' }}</p>
                            
                            <div class="mb-4">
                                <span class="inline-block bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded">
                                    Template: {{ is_object($shop) && $shop->template ? $shop->template->name : 'Non défini' }}
                                </span>
                            </div>
                            
                            <div class="space-y-2 text-sm text-gray-500">
                                @if(is_object($shop) && $shop->domain)
                                    <p><strong>Domaine:</strong> {{ $shop->domain }}</p>
                                @endif
                                <p><strong>URL:</strong> /shop/{{ is_object($shop) ? $shop->slug : 'slug' }}</p>
                                <p><strong>Produits:</strong> {{ is_object($shop) ? $shop->products->count() : 0 }}</p>
                            </div>
                            
                            <div class="mt-6 space-y-2">
                                <a href="/shop/{{ is_object($shop) ? $shop->slug : 'horizon-fashion' }}" class="block w-full bg-blue-600 text-white py-2 px-4 rounded-md font-semibold hover:bg-blue-700 transition-colors text-center">
                                    Voir la boutique
                                </a>
                                
                                @if(is_object($shop) && $shop->domain)
                                    <a href="https://{{ $shop->domain }}" target="_blank" class="block w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-md font-semibold hover:bg-gray-200 transition-colors text-center">
                                        Domaine personnalisé
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Features Section -->
            <div class="mt-16 bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Fonctionnalités démontrées</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Multi-boutiques</h4>
                        <p class="text-gray-600">Chaque boutique a son propre design et ses produits</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">E-commerce complet</h4>
                        <p class="text-gray-600">Panier, commandes, filtres et paiement bancaire</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Templates personnalisables</h4>
                        <p class="text-gray-600">Thèmes, couleurs et mise en page adaptables</p>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-blue-900 mb-4">Comment tester :</h4>
                <ol class="list-decimal list-inside space-y-2 text-blue-800">
                    <li>Cliquez sur "Voir la boutique" pour accéder à l'interface client</li>
                    <li>Parcourez les produits et ajoutez-en au panier</li>
                    <li>Testez les filtres et le tri des produits</li>
                    <li>Passez une commande pour voir le processus de paiement</li>
                    <li>Vérifiez les informations bancaires de chaque boutique</li>
                </ol>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p>&copy; 2024 MyShopify. Démonstration de plateforme multi-boutiques.</p>
            </div>
        </footer>
    </div>
</body>
</html> 