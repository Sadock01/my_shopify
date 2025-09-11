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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
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
                       class="text-gray-600 hover:text-black transition-colors duration-200 relative group">
                        <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        @if(session()->has('cart_' . $shop->id) && count(session('cart_' . $shop->id)) > 0)
                            <span class="absolute -top-2 -right-2 bg-black text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-medium">
                                {{ count(session('cart_' . $shop->id)) }}
                            </span>
                        @endif
                    </a>
                    
                    <!-- Auth Status -->
                    @auth
                        <div class="relative group">
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
                    <button class="md:hidden text-gray-600 hover:text-black transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @include('components.connected-shops')
        @yield('content')
    </main>

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
    </script>
    
    @stack('scripts')
</body>
</html> 