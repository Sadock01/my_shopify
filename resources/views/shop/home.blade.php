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

        <!-- Testimonials Section -->
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-light tracking-tight text-black mb-4">Ce que disent nos clients</h2>
                <p class="text-lg text-gray-600">Témoignages de nos clients satisfaits</p>
            </div>
            
            <!-- Carrousel des avis -->
            <div class="relative overflow-hidden">
                <div class="flex transition-transform duration-500 ease-in-out" id="testimonials-carousel">
                    <!-- Testimonial 1 -->
                    <div class="w-full flex-shrink-0 px-4">
                        <div class="bg-gray-50 p-6 rounded-xl text-center max-w-md mx-auto">
                            <div class="flex justify-center text-yellow-400 mb-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <p class="text-gray-700 text-sm mb-3">"Excellente qualité de produits et service client impeccable. Je recommande vivement !"</p>
                            <p class="text-black font-medium text-sm">Marie L.</p>
                        </div>
                    </div>
                    
                    <!-- Testimonial 2 -->
                    <div class="w-full flex-shrink-0 px-4">
                        <div class="bg-gray-50 p-6 rounded-xl text-center max-w-md mx-auto">
                            <div class="flex justify-center text-yellow-400 mb-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <p class="text-gray-700 text-sm mb-3">"Produits de qualité et service client au top !"</p>
                            <p class="text-black font-medium text-sm">Thomas B.</p>
                        </div>
                    </div>
                    
                    <!-- Testimonial 3 -->
                    <div class="w-full flex-shrink-0 px-4">
                        <div class="bg-gray-50 p-6 rounded-xl text-center max-w-md mx-auto">
                            <div class="flex justify-center text-yellow-400 mb-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <p class="text-gray-700 text-sm mb-3">"Première commande parfaite ! Je reviendrai certainement."</p>
                            <p class="text-black font-medium text-sm">Sophie M.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation du carrousel -->
                <button class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 p-2 rounded-full shadow-lg transition-all duration-200" onclick="prevTestimonial()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 p-2 rounded-full shadow-lg transition-all duration-200" onclick="nextTestimonial()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                
                <!-- Indicateurs -->
                <div class="flex justify-center mt-6 space-x-2">
                    <button class="w-2 h-2 bg-gray-300 rounded-full transition-all duration-200" onclick="goToTestimonial(0)"></button>
                    <button class="w-2 h-2 bg-gray-300 rounded-full transition-all duration-200" onclick="goToTestimonial(1)"></button>
                    <button class="w-2 h-2 bg-gray-300 rounded-full transition-all duration-200" onclick="goToTestimonial(2)"></button>
                </div>
            </div>
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

@push('scripts')
<script>
    // Carrousel des avis
    let currentTestimonial = 0;
    const totalTestimonials = 3;
    
    function showTestimonial(index) {
        const carousel = document.getElementById('testimonials-carousel');
        const indicators = document.querySelectorAll('.flex.justify-center.mt-6 button');
        
        // Mettre à jour la position du carrousel
        carousel.style.transform = `translateX(-${index * 100}%)`;
        
        // Mettre à jour les indicateurs
        indicators.forEach((indicator, i) => {
            if (i === index) {
                indicator.classList.remove('bg-gray-300');
                indicator.classList.add('bg-black');
            } else {
                indicator.classList.remove('bg-black');
                indicator.classList.add('bg-gray-300');
            }
        });
        
        currentTestimonial = index;
    }
    
    function nextTestimonial() {
        const next = (currentTestimonial + 1) % totalTestimonials;
        showTestimonial(next);
    }
    
    function prevTestimonial() {
        const prev = (currentTestimonial - 1 + totalTestimonials) % totalTestimonials;
        showTestimonial(prev);
    }
    
    function goToTestimonial(index) {
        showTestimonial(index);
    }
    
    // Auto-play du carrousel
    setInterval(nextTestimonial, 5000);
    
    // Initialiser le premier avis
    document.addEventListener('DOMContentLoaded', function() {
        showTestimonial(0);
    });
</script>
@endpush 