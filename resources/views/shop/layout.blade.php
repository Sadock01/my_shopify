<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $shop->name }} - @yield('title', 'Boutique en ligne')</title>
    
    <!-- Meta tags -->
    <meta name="description" content="{{ $shop->description }}">
    <meta name="keywords" content="{{ $shop->name }}, boutique en ligne, e-commerce">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ $shop->favicon ? Storage::url($shop->favicon) : ($shop->logo ? Storage::url($shop->logo) : '/favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ $shop->favicon ? Storage::url($shop->favicon) : ($shop->logo ? Storage::url($shop->logo) : '/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ $shop->favicon ? Storage::url($shop->favicon) : ($shop->logo ? Storage::url($shop->logo) : '/favicon.ico') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js pour les notifications -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
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
            font-family: 'Montserrat', '{{ $shop->theme_settings['font_family'] ?? 'Inter' }}', sans-serif;
            color: var(--text-color);
            font-weight: 300;
            letter-spacing: 0.01em;
            line-height: 1.6;
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
        
        /* Montserrat typography enhancements */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            letter-spacing: 0.02em;
            line-height: 1.3;
        }
        
        .font-light {
            font-weight: 300;
            letter-spacing: 0.02em;
        }
        
        .font-medium {
            font-weight: 400;
            letter-spacing: 0.01em;
        }
        
        .font-semibold {
            font-weight: 500;
            letter-spacing: 0.005em;
        }
        
        /* Soft button styles */
        .btn {
            font-family: 'Montserrat', sans-serif;
            font-weight: 400;
            letter-spacing: 0.01em;
        }
        
        /* Navigation improvements */
        nav a {
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            letter-spacing: 0.02em;
        }
        
        /* Product card text */
        .product-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            letter-spacing: 0.01em;
        }
        
        .product-description {
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            letter-spacing: 0.01em;
            line-height: 1.5;
        }
        
        /* Products Grid - 1 produit par ligne ≤600px, 4 max >600px */
        .products-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: 1fr;
        }
        
        @media (min-width: 600px) {
            .products-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                max-width: 100%;
            }
        }
        
        /* Limiter à 4 colonnes maximum */
        @media (min-width: 1200px) {
            .products-grid {
                grid-template-columns: repeat(4, 1fr);
                max-width: 1200px;
                margin: 0 auto;
            }
        }
        
        /* Product card optimizations for responsive grid */
        .product-card {
            transform: scale(1);
            transition: all 0.3s ease;
            min-height: 300px;
            display: flex;
            flex-direction: column;
        }
        
        .product-card:hover {
            transform: scale(1.02);
            z-index: 10;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .product-card .product-image {
            height: 180px;
            object-fit: cover;
            width: 100%;
        }
        
        /* Mobile: image plus grande pour 1 colonne */
        @media (max-width: 600px) {
            .product-card {
                min-height: 400px;
            }
            
            .product-card .product-image {
                height: 250px;
            }
        }
        
        /* Desktop: image optimisée pour 4 colonnes */
        @media (min-width: 1200px) {
            .product-card .product-image {
                height: 160px;
            }
        }
        
        .product-card .product-title {
            font-size: 1rem;
            line-height: 1.3;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .product-card .product-description {
            font-size: 0.85rem;
            line-height: 1.4;
            margin-bottom: 0.75rem;
            flex-grow: 1;
        }
        
        .product-card .product-price {
            font-size: 1.1rem;
            font-weight: 500;
        }
        
        .product-card .product-actions {
            font-size: 0.85rem;
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
                <a href="{{ $shop->isOnCustomDomain() ? route('shop.home') : route('shop.home.slug', ['shop' => $shop->slug]) }}" class="flex items-center hover:opacity-80 transition-opacity duration-200">
                    @if($shop->logo)
                        <img src="{{ Storage::url($shop->logo) }}" alt="{{ $shop->name }}" class="h-8 w-auto">
                    @else
                        <h1 class="text-xl font-light tracking-wide text-black">{{ $shop->name }}</h1>
                    @endif
                </a>
                
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
                        <a href="{{ $shop->isOnCustomDomain() ? route('shop.home') . '#about' : route('shop.home.slug', $shop->slug) . '#about' }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200 font-light">
                            À propos
                        </a>
                    @endif
                    @if($shop->contact_email)
                        <a href="{{ $shop->isOnCustomDomain() ? route('shop.home') . '#contact' : route('shop.home.slug', $shop->slug) . '#contact' }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200 font-light">
                            Contact
                        </a>
                    @endif
                </nav>
                
                <!-- Actions -->
                <div class="flex items-center space-x-6">
                    <!-- Cart - Masqué sur mobile -->
                    <a href="{{ $shop->isOnCustomDomain() ? route('shop.cart') : '/shop/'.$shop->slug.'/cart' }}" 
                       class="hidden md:block text-gray-600 hover:text-black transition-colors duration-200 relative group">
                        <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <!-- Compteur de panier -->
                        <span id="cart-count-{{ $shop->id }}" class="absolute -top-2 -right-2 bg-black text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-semibold" style="display: none;">
                            0
                        </span>
                    </a>
                    
                    <!-- Auth Status - Masqué sur mobile -->
                    @auth
                        <div class="hidden md:block relative group">
                            <button class="flex items-center space-x-2 text-gray-600 hover:text-black transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="py-2">
                                    <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-100">
                                        Connecté en tant que<br>
                                        <span class="font-medium">{{ Auth::user()->email }}</span>
                                    </div>
                                    <form method="POST" action="{{ route('shop.logout.slug', ['shop' => $shop->slug]) }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                            Se déconnecter
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('shop.login.slug', ['shop' => $shop->slug]) }}" class="text-sm text-gray-600 hover:text-black transition-colors duration-200 font-medium">
                                Connexion
                            </a>
                            <a href="{{ route('shop.register.slug', ['shop' => $shop->slug]) }}" class="bg-black text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition-colors duration-200">
                                Inscription
                            </a>
                        </div>
                    @endauth
                    
                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-button" class="md:hidden text-gray-600 hover:text-black transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden md:hidden">
        <div class="fixed inset-y-0 right-0 w-80 bg-white shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out" id="mobile-menu">
            <div class="flex flex-col h-full">
                <!-- Menu Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Menu</h2>
                    <button id="close-mobile-menu" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Menu Content -->
                <div class="flex-1 overflow-y-auto p-6">
                    <!-- Navigation Links -->
                    <div class="space-y-6 mb-8">
                        <a href="{{ route('shop.home.slug', ['shop' => $shop->slug]) }}" class="flex items-center space-x-4 text-gray-700 hover:text-primary transition-colors py-2">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                            <span class="font-medium">Accueil</span>
                        </a>
                        <a href="{{ route('shop.products.slug', ['shop' => $shop->slug]) }}" class="flex items-center space-x-4 text-gray-700 hover:text-primary transition-colors py-2">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <span class="font-medium">Produits</span>
                        </a>
                    </div>

                    <!-- User Section -->
                    @auth
                        <!-- Panier Mobile -->
                        <div class="mb-8">
                            <a href="/shop/{{ $shop->slug }}/cart" class="flex items-center space-x-4 text-gray-700 hover:text-primary transition-colors py-2">
                                <div class="w-6 h-6 flex items-center justify-center relative">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <span id="cart-count-mobile-{{ $shop->id }}" class="absolute -top-2 -right-2 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                                </div>
                                <span class="font-medium">Mon panier</span>
                            </a>
                        </div>

                        <!-- Profil Utilisateur -->
                        <div class="mt-auto p-6 bg-gray-50 rounded-xl">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                            
                            <!-- Bouton Déconnexion -->
                            <form action="{{ route('shop.logout.slug', ['shop' => $shop->slug]) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center space-x-2 text-red-600 hover:text-red-700 hover:bg-red-50 py-3 px-4 rounded-lg transition-colors font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span>Se déconnecter</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Boutons de connexion mobile -->
                        <div class="mt-auto space-y-4">
                            <a href="{{ route('shop.login.slug', ['shop' => $shop->slug]) }}" class="block w-full text-center bg-primary text-white py-3 px-6 rounded-lg font-semibold hover:opacity-90 transition-opacity">
                                Se connecter
                            </a>
                            <a href="{{ route('shop.register.slug', ['shop' => $shop->slug]) }}" class="block w-full text-center border-2 border-primary text-primary py-3 px-6 rounded-lg font-semibold hover:bg-primary hover:text-white transition-colors">
                                S'inscrire
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        @include('components.connected-shops')
        @yield('content')
    </main>

    <!-- Notifications Container -->
    <div id="notifications-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
    
    <!-- Session Notifications -->
    @include('components.session-notifications')

    <!-- Footer -->
    <footer class="bg-black text-white border-t border-gray-800">
        <div class="max-w-6xl mx-auto px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Shop Info -->
                <div class="text-center md:text-left">
                    <h3 class="text-lg font-light tracking-wide mb-2">{{ $shop->name }}</h3>
                    <p class="text-sm text-gray-400 font-light">© {{ date('Y') }} {{ $shop->name }}. Tous droits réservés.</p>
                </div>
                
                <!-- Quick Links -->
                <div class="text-center">
                    <h4 class="text-sm font-medium mb-3">Navigation</h4>
                    <div class="flex flex-col space-y-2">
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
                </div>
                
                <!-- Founder Info & Social -->
                <div class="text-center md:text-right">
                    @if($shop->owner_name)
                    <div class="mb-3">
                        <h4 class="text-sm font-medium mb-1">Fondateur</h4>
                        <p class="text-sm text-gray-300">{{ $shop->owner_name }}</p>
                        @if($shop->owner_email)
                        <p class="text-xs text-gray-400">{{ $shop->owner_email }}</p>
                        @endif
                    </div>
                    @endif
                    
                    <!-- Social Links -->
                    @if($shop->social_links)
                    <div class="flex justify-center md:justify-end space-x-3">
                        @if(isset($shop->social_links['instagram']))
                            <a href="{{ $shop->social_links['instagram'] }}" 
                               class="text-gray-400 hover:text-white transition-colors duration-200">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058 1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                        @endif
                        @if(isset($shop->social_links['facebook']))
                            <a href="{{ $shop->social_links['facebook'] }}" 
                               class="text-gray-400 hover:text-white transition-colors duration-200">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                        @endif
                        @if(isset($shop->social_links['twitter']))
                            <a href="{{ $shop->social_links['twitter'] }}" 
                               class="text-gray-400 hover:text-white transition-colors duration-200">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Vérifier si l'utilisateur a changé et vider le localStorage si nécessaire
        document.addEventListener('DOMContentLoaded', function() {
            const currentUserId = {{ Auth::id() ?? 'null' }};
            const storedUserId = localStorage.getItem('current_user_id');
            
            if (storedUserId && storedUserId !== currentUserId.toString()) {
                // L'utilisateur a changé, vider le panier localStorage
                localStorage.removeItem('cart_{{ $shop->id }}');
                localStorage.removeItem('current_user_id');
                console.log('Utilisateur changé, panier localStorage vidé');
                
                // Forcer la mise à jour du compteur
                const cartBadge = document.getElementById('cart-count-{{ $shop->id }}');
                if (cartBadge) {
                    cartBadge.textContent = '0';
                    cartBadge.style.display = 'none';
                }
            }
            
            // Stocker l'ID de l'utilisateur actuel
            if (currentUserId) {
                localStorage.setItem('current_user_id', currentUserId.toString());
            } else {
                // Si pas d'utilisateur connecté, vider le localStorage
                localStorage.removeItem('current_user_id');
            }
        });

        // Add to cart functionality
        function addToCart(productId, quantity = 1, productInfo = null) {
            console.log('=== addToCart appelé ===');
            console.log('Product ID:', productId);
            console.log('Quantity:', quantity);
            console.log('Product Info:', productInfo);
            
            // S'assurer que quantity est un nombre
            quantity = parseInt(quantity) || 1;
            
            const cart = JSON.parse(localStorage.getItem('cart_{{ $shop->id }}') || '[]');
            console.log('Panier avant ajout:', cart);
            
            const existingItem = cart.find(item => item.product_id === productId);
            console.log('Article existant trouvé:', existingItem);
            
            if (existingItem) {
                console.log('Quantité avant:', existingItem.quantity);
                existingItem.quantity = parseInt(existingItem.quantity) + quantity;
                console.log('Quantité après:', existingItem.quantity);
            } else {
                console.log('Nouvel article à ajouter');
                // Si on a les informations du produit, les stocker directement
                if (productInfo) {
                    cart.push({
                        product_id: productId,
                        quantity: quantity,
                        name: productInfo.name,
                        price: productInfo.price,
                        image: productInfo.image,
                        category: productInfo.category
                    });
                } else {
                    // Sinon, stocker seulement l'ID (ancienne méthode)
                    cart.push({ product_id: productId, quantity: quantity });
                }
            }
            
            console.log('Panier après ajout:', cart);
            localStorage.setItem('cart_{{ $shop->id }}', JSON.stringify(cart));
            
            // Update cart count
            updateCartCount();
            
            // Nettoyer le panier pour éviter les doublons
            cleanCart();
            
            // Show success notification
            if (window.showSuccess) {
                window.showSuccess('Produit ajouté au panier !');
            } else {
                alert('Produit ajouté au panier !');
            }
        }
        
        // Fonction pour nettoyer le panier et éviter les doublons
        function cleanCart() {
            const cart = JSON.parse(localStorage.getItem('cart_{{ $shop->id }}') || '[]');
            console.log('=== NETTOYAGE DU PANIER ===');
            console.log('Panier avant nettoyage:', cart);
            
            // Grouper les articles par product_id et additionner les quantités
            const cleanedCart = [];
            const productMap = new Map();
            
            cart.forEach(item => {
                const productId = item.product_id;
                if (productMap.has(productId)) {
                    // Article existant, additionner la quantité
                    const existingItem = productMap.get(productId);
                    existingItem.quantity = parseInt(existingItem.quantity) + parseInt(item.quantity);
                    console.log(`Quantité accumulée pour produit ${productId}:`, existingItem.quantity);
                } else {
                    // Nouvel article
                    productMap.set(productId, { ...item });
                }
            });
            
            // Convertir la Map en Array
            cleanedCart.push(...productMap.values());
            
            console.log('Panier après nettoyage:', cleanedCart);
            
            // Sauvegarder le panier nettoyé
            localStorage.setItem('cart_{{ $shop->id }}', JSON.stringify(cleanedCart));
            
            // Mettre à jour le compteur
            updateCartCount();
        }
        
        function updateCartCount() {
            // Vérifier si l'utilisateur est connecté
            const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
            
            if (!isAuthenticated) {
                return;
            }
            
            console.log('updateCartCount appelé, isAuthenticated:', isAuthenticated);
            
            if (isAuthenticated) {
                // Utilisateur connecté - vérifier d'abord le localStorage
                const localStorageCart = JSON.parse(localStorage.getItem('cart_{{ $shop->id }}') || '[]');
                const localStorageCount = localStorageCart.reduce((total, item) => total + parseInt(item.quantity), 0);
                
                if (localStorageCount > 0) {
                    // Si le localStorage a des produits, l'utiliser
                    console.log('Utilisation du localStorage pour le compteur:', localStorageCount);
                    const cartBadge = document.getElementById('cart-count-{{ $shop->id }}');
                    const cartBadgeMobile = document.getElementById('cart-count-mobile-{{ $shop->id }}');
                    
                    if (cartBadge) {
                        cartBadge.textContent = localStorageCount;
                        cartBadge.style.display = 'flex';
                    }
                    if (cartBadgeMobile) {
                        cartBadgeMobile.textContent = localStorageCount;
                        cartBadgeMobile.style.display = 'flex';
                    }
                } else {
                    // Sinon, récupérer le compteur via AJAX
                    const cartCountRoute = '{{ $shop->isOnCustomDomain() ? route("shop.cart-count") : route("shop.cart-count.slug", $shop->slug) }}';
                    console.log('Route AJAX:', cartCountRoute);
                    fetch(cartCountRoute)
                        .then(response => response.json())
                        .then(data => {
                            console.log('Réponse AJAX:', data);
                            const cartBadge = document.getElementById('cart-count-{{ $shop->id }}');
                            const cartBadgeMobile = document.getElementById('cart-count-mobile-{{ $shop->id }}');
                            
                            console.log('Element cartBadge trouvé:', cartBadge);
                            if (cartBadge) {
                                cartBadge.textContent = data.count;
                                cartBadge.style.display = data.count > 0 ? 'flex' : 'none';
                                console.log('Compteur mis à jour:', data.count);
                            }
                            if (cartBadgeMobile) {
                                cartBadgeMobile.textContent = data.count;
                                cartBadgeMobile.style.display = data.count > 0 ? 'flex' : 'none';
                            }
                        })
                        .catch(error => {
                            console.log('Erreur lors de la récupération du compteur du panier:', error);
                        });
                }
            } else {
                // Utilisateur non connecté - compter depuis le localStorage
                const cart = JSON.parse(localStorage.getItem('cart_{{ $shop->id }}') || '[]');
                const count = cart.reduce((total, item) => total + parseInt(item.quantity), 0);
                console.log('Panier localStorage:', cart, 'Count:', count);
                
                const cartBadge = document.getElementById('cart-count-{{ $shop->id }}');
                const cartBadgeMobile = document.getElementById('cart-count-mobile-{{ $shop->id }}');
                console.log('Element cartBadge trouvé:', cartBadge);
                if (cartBadge) {
                    cartBadge.textContent = count;
                    cartBadge.style.display = count > 0 ? 'flex' : 'none';
                    console.log('Compteur mis à jour:', count);
                }
                if (cartBadgeMobile) {
                    cartBadgeMobile.textContent = count;
                    cartBadgeMobile.style.display = count > 0 ? 'flex' : 'none';
                }
            }
        }
        
        // Fonction globale pour mettre à jour les compteurs de panier
        window.updateCartCounters = function() {
            updateCartCount();
        };
        
        // Mettre à jour les compteurs quand on navigue (pour les SPA)
        window.addEventListener('popstate', function() {
            setTimeout(updateCartCount, 100);
        });
        
        // Mettre à jour les compteurs toutes les 5 secondes (au cas où)
        // setInterval(updateCartCount, 5000); // Désactivé pour éviter les boucles
        
        // Initialize cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== INITIALISATION COMPTEUR PANIER ===');
            
            // Nettoyer le panier au chargement de la page
            cleanCart();
            
            updateCartCount();
        });

        // Session management for shop pages
        @auth
        let sessionWarningShown = false;
        
        // Function to refresh CSRF token
        function refreshCSRFToken() {
            fetch('/refresh-csrf-token', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    // Update all CSRF tokens in forms
                    document.querySelectorAll('input[name="_token"]').forEach(input => {
                        input.value = data.token;
                    });
                    
                    // Update meta tag
                    const metaToken = document.querySelector('meta[name="csrf-token"]');
                    if (metaToken) {
                        metaToken.setAttribute('content', data.token);
                    }
                }
            })
            .catch(error => {
                console.log('CSRF token refresh failed:', error);
            });
        }
        
        // Function to check session status
        function checkSessionStatus() {
            fetch('/check-session', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.status === 401) {
                    // Session expired
                    showSessionExpiredModal();
                }
            })
            .catch(error => {
                console.log('Session check failed:', error);
            });
        }
        
        // Function to show session expired modal
        function showSessionExpiredModal() {
            if (sessionWarningShown) return;
            sessionWarningShown = true;
            
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white rounded-lg p-6 max-w-md mx-4">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Session Expirée</h3>
                    </div>
                    <p class="text-gray-600 mb-6">Votre session a expiré. Veuillez vous reconnecter pour continuer vos achats.</p>
                    <div class="flex justify-end space-x-3">
                        <button onclick="this.closest('.fixed').remove(); window.location.href='/login';" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Se reconnecter
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        // Refresh CSRF token every 30 minutes
        setInterval(refreshCSRFToken, 30 * 60 * 1000);
        
        // Check session status every 5 minutes
        setInterval(checkSessionStatus, 5 * 60 * 1000);
        
        // Refresh CSRF token on page load
        document.addEventListener('DOMContentLoaded', function() {
            refreshCSRFToken();
        });
        @endauth
        
        // Mobile Menu Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
            const mobileMenu = document.getElementById('mobile-menu');
            const closeMobileMenu = document.getElementById('close-mobile-menu');
            
            // Open mobile menu
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenuOverlay.classList.remove('hidden');
                    setTimeout(() => {
                        mobileMenu.classList.remove('translate-x-full');
                    }, 10);
                });
            }
            
            // Close mobile menu
            function closeMenu() {
                mobileMenu.classList.add('translate-x-full');
                setTimeout(() => {
                    mobileMenuOverlay.classList.add('hidden');
                }, 300);
            }
            
            if (closeMobileMenu) {
                closeMobileMenu.addEventListener('click', closeMenu);
            }
            
            // Close menu when clicking overlay
            if (mobileMenuOverlay) {
                mobileMenuOverlay.addEventListener('click', function(e) {
                    if (e.target === mobileMenuOverlay) {
                        closeMenu();
                    }
                });
            }
            
            // Close menu on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !mobileMenuOverlay.classList.contains('hidden')) {
                    closeMenu();
                }
            });
        });
    </script>
    
    <!-- Notifications Service -->
    <script src="{{ asset('js/notifications.js') }}"></script>
    
    @stack('scripts')
</body>
</html> 