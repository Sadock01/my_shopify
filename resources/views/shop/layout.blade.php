<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $shop->name }} - @yield('title', 'Boutique en ligne')</title>
    
    <!-- Meta tags -->
    <meta name="description" content="{{ $shop->description }}">
    <meta name="keywords" content="{{ $shop->name }}, boutique en ligne, e-commerce">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ $shop->logo ?? '/favicon.ico' }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: {{ $shop->theme_settings['primary_color'] ?? '#000000' }};
            --secondary-color: {{ $shop->theme_settings['secondary_color'] ?? '#ffffff' }};
            --accent-color: {{ $shop->theme_settings['accent_color'] ?? '#f8f9fa' }};
            --text-color: {{ $shop->theme_settings['text_color'] ?? '#333333' }};
            --text-light: {{ $shop->theme_settings['text_light'] ?? '#666666' }};
        }
        
        body {
            font-family: '{{ $shop->theme_settings['font_family'] ?? 'Inter' }}', sans-serif;
            color: var(--text-color);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: var(--secondary-color);
        }
        
        .btn-primary:hover {
            opacity: 0.9;
        }
        
        .text-primary {
            color: var(--primary-color);
        }
        
        .bg-primary {
            background-color: var(--primary-color);
        }
        
        .border-primary {
            border-color: var(--primary-color);
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes bounceIn {
            from { opacity: 0; transform: scale(0.3); }
            50% { opacity: 1; transform: scale(1.05); }
            to { opacity: 1; transform: scale(1); }
        }
        
        .animate-fade-in {
            animation: fadeIn 1s ease-out;
        }
        
        .animate-fade-in-delay {
            animation: fadeIn 1s ease-out 0.3s both;
        }
        
        .animate-bounce-in {
            animation: bounceIn 1s ease-out;
        }
        
        .payment-method-card {
            animation: fadeIn 0.6s ease-out;
        }
        
        .payment-method-card:nth-child(1) { animation-delay: 0.1s; }
        .payment-method-card:nth-child(2) { animation-delay: 0.2s; }
        .payment-method-card:nth-child(3) { animation-delay: 0.3s; }
        .payment-method-card:nth-child(4) { animation-delay: 0.4s; }
        .payment-method-card:nth-child(5) { animation-delay: 0.5s; }
        .payment-method-card:nth-child(6) { animation-delay: 0.6s; }
        
        /* Line clamp utility */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-white">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 backdrop-blur-sm bg-white/95">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    @if($shop->logo)
                        <img src="{{ $shop->logo }}" alt="{{ $shop->name }}" class="h-8 w-auto">
                    @else
                        <h1 class="text-xl font-light tracking-wide text-black">{{ $shop->name }}</h1>
                    @endif
                </div>
                
                <!-- Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ $shop->isOnCustomDomain() ? route('shop.home') : route('shop.home.slug', $shop->slug) }}" 
                       class="text-sm text-gray-600 hover:text-black transition-colors duration-200 font-light">
                        Accueil
                    </a>
                    <a href="{{ $shop->isOnCustomDomain() ? route('shop.products') : route('shop.products.slug', $shop->slug) }}" 
                       class="text-sm text-gray-600 hover:text-black transition-colors duration-200 font-light">
                        Produits
                    </a>
                    @if($shop->about_text)
                        <a href="#about" class="text-sm text-gray-600 hover:text-black transition-colors duration-200 font-light">
                            À propos
                        </a>
                    @endif
                    @if($shop->contact_email)
                        <a href="#contact" class="text-sm text-gray-600 hover:text-black transition-colors duration-200 font-light">
                            Contact
                        </a>
                    @endif
                </nav>
                
                <!-- Actions -->
                <div class="flex items-center space-x-6">
                    <!-- Cart -->
                    <a href="{{ $shop->isOnCustomDomain() ? route('shop.cart') : route('shop.cart.slug', $shop->slug) }}" 
                       class="text-gray-600 hover:text-black transition-colors duration-200 relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        @if(session()->has('cart_' . $shop->id) && count(session('cart_' . $shop->id)) > 0)
                            <span class="absolute -top-2 -right-2 bg-black text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-light">
                                {{ count(session('cart_' . $shop->id)) }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-black text-white border-t border-gray-800">
        <div class="max-w-6xl mx-auto px-6 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <!-- Shop Info -->
                <div class="text-center md:text-left">
                    <h3 class="text-lg font-light tracking-wide mb-2">{{ $shop->name }}</h3>
                    <p class="text-sm text-gray-400 font-light">© {{ date('Y') }} {{ $shop->name }}. Tous droits réservés.</p>
                </div>
                
                <!-- Quick Links -->
                <div class="flex space-x-8">
                    <a href="{{ $shop->isOnCustomDomain() ? route('shop.home') : route('shop.home.slug', $shop->slug) }}" 
                       class="text-sm text-gray-300 hover:text-white transition-colors duration-200 font-light">
                        Accueil
                    </a>
                    <a href="{{ $shop->isOnCustomDomain() ? route('shop.products') : route('shop.products.slug', $shop->slug) }}" 
                       class="text-sm text-gray-300 hover:text-white transition-colors duration-200 font-light">
                        Produits
                    </a>
                    @if($shop->contact_email)
                    <a href="mailto:{{ $shop->contact_email }}" 
                       class="text-sm text-gray-300 hover:text-white transition-colors duration-200 font-light">
                        Contact
                    </a>
                    @endif
                </div>
                
                <!-- Social Links -->
                @if($shop->social_links)
                <div class="flex space-x-4">
                    @if(isset($shop->social_links['instagram']))
                        <a href="{{ $shop->social_links['instagram'] }}" 
                           class="text-gray-400 hover:text-white transition-colors duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    @endif
                    @if(isset($shop->social_links['facebook']))
                        <a href="{{ $shop->social_links['facebook'] }}" 
                           class="text-gray-400 hover:text-white transition-colors duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    @endif
                    @if(isset($shop->social_links['twitter']))
                        <a href="{{ $shop->social_links['twitter'] }}" 
                           class="text-gray-400 hover:text-white transition-colors duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Add to cart functionality
        function addToCart(productId, quantity = 1) {
            const cart = JSON.parse(localStorage.getItem('cart_{{ $shop->id }}') || '[]');
            const existingItem = cart.find(item => item.product_id === productId);
            
            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                cart.push({ product_id: productId, quantity: quantity });
            }
            
            localStorage.setItem('cart_{{ $shop->id }}', JSON.stringify(cart));
            
            // Update cart count
            updateCartCount();
            
            // Show success message
            alert('Produit ajouté au panier !');
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
        
        // Initialize cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });
    </script>
    
    @stack('scripts')
</body>
</html> 