@extends('shop.layout')

@section('title', 'Accueil')

@section('content')


    <!-- Hero Section -->
    <section class="relative bg-black text-white overflow-hidden">
        @if($shop->banner_image)
            <div class="absolute inset-0">
                <img src="{{ $shop->banner_image }}" alt="{{ $shop->name }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/50"></div>
            </div>
        @endif
        
        <div class="relative max-w-6xl mx-auto px-6 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-light tracking-wide mb-6 animate-fade-in">
                    {{ $shop->name }}
                </h1>
                <p class="text-xl mb-8 text-gray-300 font-light animate-fade-in-delay max-w-2xl mx-auto">
                    Découvrez notre sélection de produits d'exception
                </p>
                <a href="{{ $shop->isOnCustomDomain() ? route('shop.products') : route('shop.products.slug', $shop->slug) }}" 
                   class="inline-block bg-white text-black px-8 py-3 font-light tracking-wide hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 animate-bounce-in">
                    Découvrir nos produits
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-light tracking-wide text-black mb-4">Produits vedettes</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Sélection de nos produits les plus appréciés</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($featuredProducts as $product)
                    <div class="group bg-white border border-gray-200 hover:border-gray-300 transition-all duration-300">
                        <div class="relative overflow-hidden">
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                            
                            <!-- Quick Add Button -->
                            <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <button onclick="addToCart({{ $product->id }})" class="bg-black text-white p-2 rounded-full shadow-lg hover:bg-gray-800 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Discount Badge -->
                            @if($product->original_price && $product->original_price > $product->price)
                                <div class="absolute top-4 left-4">
                                    <span class="bg-black text-white px-2 py-1 text-xs font-light">
                                        -{{ round((($product->original_price - $product->price) / $product->original_price) * 100) }}%
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <h3 class="text-lg font-light text-black mb-2 group-hover:text-gray-600 transition-colors">{{ $product->name }}</h3>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ Str::limit($product->description, 60) }}</p>
                            
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-2">
                                    @if($product->original_price && $product->original_price > $product->price)
                                        <span class="text-gray-400 line-through text-sm">{{ number_format($product->original_price, 2) }}€</span>
                                    @endif
                                    <span class="text-xl font-light text-black">{{ number_format($product->price, 2) }}€</span>
                                </div>
                                
                                <a href="{{ $shop->isOnCustomDomain() ? route('shop.product', $product->id) : route('shop.product.slug', [$shop->slug, $product->id]) }}" 
                                   class="text-black hover:text-gray-600 text-sm font-light group-hover:underline">
                                    Voir détails
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-6 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun produit vedette</h3>
                        <p class="text-gray-500">Revenez bientôt pour découvrir nos nouvelles collections.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="text-center mt-8">
                <a href="{{ $shop->isOnCustomDomain() ? route('shop.products') : route('shop.products.slug', $shop->slug) }}" 
                   class="inline-block bg-black text-white px-6 py-3 font-light tracking-wide hover:bg-gray-800 transition-all duration-300">
                    Voir tous nos produits
                </a>
            </div>
        </div>
    </section>

    <!-- Payment Methods Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-light tracking-wide text-black mb-4">Moyens de paiement</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Paiements sécurisés et flexibles
                </p>
            </div>
            
            <div class="grid grid-cols-3 md:grid-cols-6 gap-6">
                <!-- Visa -->
                <div class="payment-method-card group">
                    <div class="bg-white border border-gray-200 p-4 text-center hover:border-gray-300 transition-all duration-300">
                        <div class="w-12 h-12 bg-black rounded-lg mx-auto mb-3 flex items-center justify-center">
                            <span class="text-white font-light text-sm">VISA</span>
                        </div>
                        <p class="text-gray-800 font-light text-sm">Visa</p>
                    </div>
                </div>
                
                <!-- Mastercard -->
                <div class="payment-method-card group">
                    <div class="bg-white border border-gray-200 p-4 text-center hover:border-gray-300 transition-all duration-300">
                        <div class="w-12 h-12 bg-black rounded-lg mx-auto mb-3 flex items-center justify-center">
                            <span class="text-white font-light text-sm">MC</span>
                        </div>
                        <p class="text-gray-800 font-light text-sm">Mastercard</p>
                    </div>
                </div>
                
                <!-- PayPal -->
                <div class="payment-method-card group">
                    <div class="bg-white border border-gray-200 p-4 text-center hover:border-gray-300 transition-all duration-300">
                        <div class="w-12 h-12 bg-black rounded-lg mx-auto mb-3 flex items-center justify-center">
                            <span class="text-white font-light text-xs">PayPal</span>
                        </div>
                        <p class="text-gray-800 font-light text-sm">PayPal</p>
                    </div>
                </div>
                
                <!-- Apple Pay -->
                <div class="payment-method-card group">
                    <div class="bg-white border border-gray-200 p-4 text-center hover:border-gray-300 transition-all duration-300">
                        <div class="w-12 h-12 bg-black rounded-lg mx-auto mb-3 flex items-center justify-center">
                            <span class="text-white font-light text-sm">🍎</span>
                        </div>
                        <p class="text-gray-800 font-light text-sm">Apple Pay</p>
                    </div>
                </div>
                
                <!-- Google Pay -->
                <div class="payment-method-card group">
                    <div class="bg-white border border-gray-200 p-4 text-center hover:border-gray-300 transition-all duration-300">
                        <div class="w-12 h-12 bg-black rounded-lg mx-auto mb-3 flex items-center justify-center">
                            <span class="text-white font-light text-sm">G</span>
                        </div>
                        <p class="text-gray-800 font-light text-sm">Google Pay</p>
                    </div>
                </div>
                
                <!-- Virement -->
                <div class="payment-method-card group">
                    <div class="bg-white border border-gray-200 p-4 text-center hover:border-gray-300 transition-all duration-300">
                        <div class="w-12 h-12 bg-black rounded-lg mx-auto mb-3 flex items-center justify-center">
                            <span class="text-white font-light text-sm">€</span>
                        </div>
                        <p class="text-gray-800 font-light text-sm">Virement</p>
                    </div>
                </div>
            </div>
            

        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-16 bg-black text-white">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-light tracking-wide mb-4">
                Restez informé
            </h2>
            <p class="text-gray-300 mb-8 max-w-2xl mx-auto">
                Recevez nos dernières nouveautés et offres exclusives
            </p>
            
            <form class="flex max-w-md mx-auto">
                <input type="email" placeholder="Votre email" 
                       class="flex-1 px-4 py-3 bg-white text-black placeholder-gray-500 focus:outline-none">
                <button type="submit" 
                        class="bg-white text-black px-6 py-3 font-light tracking-wide hover:bg-gray-100 transition-all duration-300">
                    S'abonner
                </button>
            </form>
        </div>
    </section>

    <!-- About Section -->
    @if($shop->about_text)
    <section id="about" class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center">
                <h2 class="text-3xl font-light tracking-wide text-black mb-6">À propos</h2>
                <p class="text-gray-600 leading-relaxed max-w-2xl mx-auto">{{ $shop->about_text }}</p>
            </div>
        </div>
    </section>
    @endif

    <!-- Owner Section -->
    @if($shop->owner_name)
    <section class="py-20 bg-gradient-to-br from-gray-50 to-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Rencontrez le fondateur</h2>
                <p class="text-xl text-gray-600">Découvrez la personne derrière {{ $shop->name }}</p>
            </div>
            
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <!-- Owner Image Placeholder -->
                    <div class="bg-gradient-to-br from-purple-600 to-pink-600 p-12 flex items-center justify-center">
                        <div class="text-center text-white">
                            <div class="w-32 h-32 bg-white/20 rounded-full mx-auto mb-6 flex items-center justify-center">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-2">{{ $shop->owner_name }}</h3>
                            <p class="text-purple-200">Fondateur & Propriétaire</p>
                        </div>
                    </div>
                    
                    <!-- Owner Info -->
                    <div class="p-12">
                        @if($shop->owner_bio)
                        <div class="mb-8">
                            <h4 class="text-xl font-semibold text-gray-900 mb-4">Biographie</h4>
                            <p class="text-gray-600 leading-relaxed">{{ $shop->owner_bio }}</p>
                        </div>
                        @endif
                        
                        <div class="space-y-4">
                            @if($shop->owner_email)
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p class="text-gray-900 font-medium">{{ $shop->owner_email }}</p>
                                </div>
                            </div>
                            @endif
                            
                            @if($shop->owner_phone)
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Téléphone</p>
                                    <p class="text-gray-900 font-medium">{{ $shop->owner_phone }}</p>
                                </div>
                            </div>
                            @endif
                            
                            @if($shop->owner_website)
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Site web</p>
                                    <a href="{{ $shop->owner_website }}" target="_blank" class="text-purple-600 hover:text-purple-800 font-medium">Visiter le site</a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Contact Section -->
    <section id="contact" class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-light tracking-wide text-black mb-4">Contact</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Nous sommes là pour vous aider</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @if($shop->contact_email)
                <div class="text-center">
                    <h3 class="text-lg font-light text-black mb-2">Email</h3>
                    <p class="text-gray-600">{{ $shop->contact_email }}</p>
                </div>
                @endif
                
                @if($shop->contact_phone)
                <div class="text-center">
                    <h3 class="text-lg font-light text-black mb-2">Téléphone</h3>
                    <p class="text-gray-600">{{ $shop->contact_phone }}</p>
                </div>
                @endif
            </div>
        </div>
    </section>
@endsection 