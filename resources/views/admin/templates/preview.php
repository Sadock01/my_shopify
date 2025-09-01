@extends('admin.layout')

@section('title', 'Aperçu du Template')
@section('page-title', 'Aperçu du Template')
@section('page-description', 'Visualisez l\'apparence de ce template')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- En-tête avec navigation -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Aperçu du Template</h1>
            <p class="text-gray-600 mt-2">Template: <span class="font-semibold text-blue-600">{{ ucfirst($template) }}</span></p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.templates.index') }}" 
               class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                ← Retour aux Templates
            </a>
            <a href="{{ route('admin.templates.customize', $template) }}" 
               class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-sm">
                ✏️ Personnaliser
            </a>
        </div>
    </div>

    <!-- Aperçu du Template -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
        <!-- Barre d'outils de prévisualisation -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <span class="text-lg font-semibold text-gray-800">Aperçu:</span>
                    <div class="flex space-x-3">
                        <button class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg font-medium shadow-sm">
                            🖥️ Desktop
                        </button>
                        <button class="px-4 py-2 text-sm text-gray-600 hover:bg-white hover:shadow-sm rounded-lg transition-all">
                            📱 Mobile
                        </button>
                        <button class="px-4 py-2 text-sm text-gray-600 hover:bg-white hover:shadow-sm rounded-lg transition-all">
                            📱 Tablet
                        </button>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-sm text-gray-600 font-medium">Zoom:</span>
                    <button class="w-8 h-8 text-gray-600 hover:bg-white hover:shadow-sm rounded-lg transition-all flex items-center justify-center">−</button>
                    <span class="text-sm font-bold text-gray-800 w-12 text-center">100%</span>
                    <button class="w-8 h-8 text-gray-600 hover:bg-white hover:shadow-sm rounded-lg transition-all flex items-center justify-center">+</button>
                </div>
            </div>
        </div>

        <!-- Contenu de l'aperçu -->
        <div class="p-12 bg-gray-50">
            @if($template === 'horizon')
                <!-- Aperçu Horizon - Style Moderne & Élégant -->
                <div class="max-w-5xl mx-auto">
                    <!-- Header -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full mx-auto mb-6 flex items-center justify-center">
                            <span class="text-2xl text-white font-bold">H</span>
                        </div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-4">Horizon Fashion</h2>
                        <p class="text-xl text-gray-600 mb-6">Design moderne et élégant pour les boutiques de mode</p>
                        <div class="flex justify-center space-x-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">Moderne</span>
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium">Élégant</span>
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Mode</span>
                        </div>
                    </div>
                    
                    <!-- Section Hero -->
                    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-blue-700 rounded-2xl p-12 text-white text-center mb-8 shadow-xl">
                        <h3 class="text-3xl font-bold mb-6">Nouvelle Collection 2024</h3>
                        <p class="text-xl mb-8 opacity-90">Découvrez nos dernières tendances et styles exclusifs</p>
                        <button class="bg-white text-blue-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            🛍️ Voir la Collection
                        </button>
                    </div>

                    <!-- Grille de produits -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                        @for($i = 1; $i <= 3; $i++)
                        <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-2">
                            <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 rounded-xl mb-4 flex items-center justify-center">
                                <span class="text-4xl text-gray-400">👕</span>
                            </div>
                            <h4 class="font-bold text-gray-900 text-lg mb-3">Produit {{ $i }}</h4>
                            <p class="text-gray-600 text-sm mb-4">Description détaillée du produit avec un style moderne</p>
                            <div class="flex items-center justify-between">
                                <div class="text-2xl font-bold text-blue-600">€99.99</div>
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Ajouter
                                </button>
                            </div>
                        </div>
                        @endfor
                    </div>

                    <!-- Section Features -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Caractéristiques du Template</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-blue-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                                    <span class="text-2xl">🎨</span>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-2">Design Moderne</h4>
                                <p class="text-gray-600 text-sm">Interface élégante et contemporaine</p>
                            </div>
                            <div class="text-center">
                                <div class="w-16 h-16 bg-purple-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                                    <span class="text-2xl">📱</span>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-2">Responsive</h4>
                                <p class="text-gray-600 text-sm">Adapté à tous les appareils</p>
                            </div>
                            <div class="text-center">
                                <div class="w-16 h-16 bg-green-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                                    <span class="text-2xl">⚡</span>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-2">Performance</h4>
                                <p class="text-gray-600 text-sm">Chargement rapide et optimisé</p>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($template === 'tech')
                <!-- Aperçu Tech - Style Technologique -->
                <div class="max-w-5xl mx-auto">
                    <!-- Header -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-gray-800 to-blue-900 rounded-full mx-auto mb-6 flex items-center justify-center">
                            <span class="text-2xl text-white font-bold">T</span>
                        </div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-4">TechStore</h2>
                        <p class="text-xl text-gray-600 mb-6">Style technologique et innovant</p>
                        <div class="flex justify-center space-x-4">
                            <span class="px-3 py-1 bg-gray-800 text-white rounded-full text-sm font-medium">Tech</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">Innovation</span>
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Futur</span>
                        </div>
                    </div>
                    
                    <!-- Section Hero Tech -->
                    <div class="bg-gradient-to-r from-gray-900 via-blue-900 to-gray-800 rounded-2xl p-12 text-white text-center mb-8 shadow-xl">
                        <h3 class="text-3xl font-bold mb-6">Technologies de Demain</h3>
                        <p class="text-xl mb-8 opacity-90">Innovation et performance au service de votre avenir</p>
                        <button class="bg-blue-600 text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-blue-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            🚀 Découvrir
                        </button>
                    </div>

                    <!-- Grille de produits tech -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                        @for($i = 1; $i <= 3; $i++)
                        <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-2">
                            <div class="w-full h-48 bg-gradient-to-br from-gray-300 to-blue-200 rounded-xl mb-4 flex items-center justify-center">
                                <span class="text-4xl text-gray-500">💻</span>
                            </div>
                            <h4 class="font-bold text-gray-900 text-lg mb-3">Tech {{ $i }}</h4>
                            <p class="text-gray-600 text-sm mb-4">Produit technologique de pointe avec innovation</p>
                            <div class="flex items-center justify-between">
                                <div class="text-2xl font-bold text-blue-600">€199.99</div>
                                <button class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                    Ajouter
                                </button>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>

            @elseif($template === 'luxe')
                <!-- Aperçu Luxe - Style Premium -->
                <div class="max-w-5xl mx-auto">
                    <!-- Header -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-full mx-auto mb-6 flex items-center justify-center">
                            <span class="text-2xl text-white font-bold">L</span>
                        </div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-4">LuxeMode</h2>
                        <p class="text-xl text-gray-600 mb-6">Design premium et sophistiqué</p>
                        <div class="flex justify-center space-x-4">
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-medium">Premium</span>
                            <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-sm font-medium">Luxe</span>
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-medium">Exclusif</span>
                        </div>
                    </div>
                    
                    <!-- Section Hero Luxe -->
                    <div class="bg-gradient-to-r from-yellow-500 via-orange-500 to-red-500 rounded-2xl p-12 text-white text-center mb-8 shadow-xl">
                        <h3 class="text-3xl font-bold mb-6">Collection Exclusive</h3>
                        <p class="text-xl mb-8 opacity-90">Luxe et élégance pour des clients exigeants</p>
                        <button class="bg-white text-orange-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            ✨ Découvrir
                        </button>
                    </div>

                    <!-- Grille de produits luxe -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                        @for($i = 1; $i <= 3; $i++)
                        <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-2">
                            <div class="w-full h-48 bg-gradient-to-br from-yellow-200 to-orange-200 rounded-xl mb-4 flex items-center justify-center">
                                <span class="text-4xl text-orange-400">💎</span>
                            </div>
                            <h4 class="font-bold text-gray-900 text-lg mb-3">Luxe {{ $i }}</h4>
                            <p class="text-gray-600 text-sm mb-4">Produit premium avec finitions exceptionnelles</p>
                            <div class="flex items-center justify-between">
                                <div class="text-2xl font-bold text-orange-600">€299.99</div>
                                <button class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                                    Ajouter
                                </button>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>

            @else
                <!-- Aperçu Default - Style Classique -->
                <div class="max-w-5xl mx-auto">
                    <!-- Header -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-gray-600 to-gray-800 rounded-full mx-auto mb-6 flex items-center justify-center">
                            <span class="text-2xl text-white font-bold">D</span>
                        </div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-4">Template Classique</h2>
                        <p class="text-xl text-gray-600 mb-6">Design polyvalent et professionnel</p>
                        <div class="flex justify-center space-x-4">
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">Classique</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">Polyvalent</span>
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Professionnel</span>
                        </div>
                    </div>
                    
                    <!-- Section Hero Default -->
                    <div class="bg-gradient-to-r from-gray-700 to-gray-900 rounded-2xl p-12 text-white text-center mb-8 shadow-xl">
                        <h3 class="text-3xl font-bold mb-6">Bienvenue sur Votre Boutique</h3>
                        <p class="text-xl mb-8 opacity-90">Une expérience en ligne simple et efficace</p>
                        <button class="bg-white text-gray-800 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            🚀 Commencer
                        </button>
                    </div>

                    <!-- Grille de produits default -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                        @for($i = 1; $i <= 3; $i++)
                        <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-2">
                            <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 rounded-xl mb-4 flex items-center justify-center">
                                <span class="text-4xl text-gray-400">📦</span>
                            </div>
                            <h4 class="font-bold text-gray-900 text-lg mb-3">Produit {{ $i }}</h4>
                            <p class="text-gray-600 text-sm mb-4">Description claire et professionnelle du produit</p>
                            <div class="flex items-center justify-between">
                                <div class="text-2xl font-bold text-gray-800">€79.99</div>
                                <button class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                    Ajouter
                                </button>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.template-option {
    transition: all 0.3s ease-in-out;
}

.template-option:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>

@endsection