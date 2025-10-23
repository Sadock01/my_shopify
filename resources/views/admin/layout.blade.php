<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Admin') - MyShopify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <!-- Alpine.js pour les notifications -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { 
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            letter-spacing: 0.01em;
        }
        
        .sidebar { 
            transition: all 0.3s ease; 
        }
        .sidebar.collapsed { 
            width: 4rem; 
        }
        .sidebar.collapsed .sidebar-text { 
            display: none; 
        }
        .sidebar.collapsed .sidebar-icon { 
            margin: 0 auto; 
        }
        .main-content { 
            transition: all 0.3s ease; 
        }
        .sidebar.collapsed + .main-content { 
            margin-left: 4rem; 
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 16rem;
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
        
        /* Tablet Responsive */
        @media (min-width: 769px) and (max-width: 1024px) {
            .sidebar {
                width: 5rem;
            }
            .sidebar .sidebar-text {
                display: none;
            }
            .sidebar .sidebar-icon {
                margin: 0 auto;
            }
            .main-content {
                margin-left: 5rem;
            }
        }
        
        /* Typography improvements */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            letter-spacing: 0.02em;
        }
        
        .font-semibold {
            font-weight: 400;
        }
        
        .font-bold {
            font-weight: 500;
        }
        
        /* No shadows - clean flat design */
        .sidebar {
            box-shadow: none;
        }
        
        .main-content {
            box-shadow: none;
        }
        
        .card-shadow {
            box-shadow: none;
        }
        
        .card-shadow:hover {
            box-shadow: none;
        }
        
        .header-shadow {
            box-shadow: none;
        }
        
        .stat-card-shadow {
            box-shadow: none;
        }
        
        .stat-card-shadow:hover {
            box-shadow: none;
            transform: none;
        }
        
        .action-card-shadow {
            box-shadow: none;
        }
        
        .action-card-shadow:hover {
            box-shadow: none;
            transform: none;
        }
        
        .table-shadow {
            box-shadow: none;
        }
        
        /* Dark mode styles */
        .dark {
            background-color: #1f2937;
            color: #f9fafb;
        }
        
        .dark .sidebar {
            background-color: #111827;
            border-color: #374151;
        }
        
        .dark .sidebar .sidebar-text {
            color: #f9fafb;
        }
        
        .dark .sidebar .sidebar-item {
            color: #d1d5db;
        }
        
        .dark .sidebar .sidebar-item:hover {
            background-color: #374151;
            color: #60a5fa;
        }
        
        .dark .sidebar .sidebar-item.bg-blue-50 {
            background-color: #1e3a8a;
            color: #60a5fa;
        }
        
        .dark header {
            background-color: #111827;
            border-color: #374151;
        }
        
        .dark header h1 {
            color: #f9fafb;
        }
        
        .dark header p {
            color: #d1d5db;
        }
        
        .dark main {
            background-color: #1f2937;
        }
        
        .dark .bg-white {
            background-color: #111827;
            border-color: #374151;
        }
        
        .dark .text-gray-900 {
            color: #f9fafb;
        }
        
        .dark .text-gray-700 {
            color: #d1d5db;
        }
        
        .dark .text-gray-600 {
            color: #9ca3af;
        }
        
        .dark .text-gray-500 {
            color: #6b7280;
        }
        
        .dark .border-gray-200 {
            border-color: #374151;
        }
        
        .dark .hover\:bg-gray-100:hover {
            background-color: #374151;
        }
        
        .dark .hover\:text-gray-900:hover {
            color: #f9fafb;
        }
        
        .dark .hover\:text-gray-600:hover {
            color: #d1d5db;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar fixed left-0 top-0 h-full bg-white z-50 w-64">
        <!-- Logo -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <span class="sidebar-text text-xl font-bold text-gray-900">MyShopify</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="mt-6">
            <div class="px-4 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="sidebar-item flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700' : '' }}">
                    <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                    </svg>
                    <span class="sidebar-text font-medium">Dashboard</span>
                </a>

                <!-- Boutiques -->
                <a href="{{ route('admin.shops.index') }}" 
                   class="sidebar-item flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors {{ request()->routeIs('admin.shops.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                    <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span class="sidebar-text font-medium">Boutiques</span>
                </a>

                <!-- Templates -->
                <a href="{{ route('admin.templates.index') }}" 
                   class="sidebar-item flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors {{ request()->routeIs('admin.templates.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                    <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                    </svg>
                    <span class="sidebar-text font-medium">Templates</span>
                </a>


                <!-- Commandes -->
                <a href="{{ route('admin.orders.index') }}" 
                   class="sidebar-item flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                    <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span class="sidebar-text font-medium">Commandes</span>
                </a>

                <!-- Utilisateurs -->
                <a href="{{ route('admin.users.index') }}" 
                   class="sidebar-item flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                    <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <span class="sidebar-text font-medium">Utilisateurs</span>
                </a>

            </div>
        </nav>

        <!-- Toggle Sidebar -->
        <div class="absolute bottom-4 left-4">
            <button id="toggleSidebar" class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content ml-64 min-h-screen">
        <!-- Header -->
        <header class="bg-white border-b border-gray-200">
            <div class="flex items-center justify-between px-4 sm:px-6 py-4">
                <!-- Mobile Menu Button -->
                <button id="mobileMenuButton" class="lg:hidden p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <div class="flex-1">
                    <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-sm sm:text-base text-gray-600">@yield('page-description', 'Gérez vos boutiques et votre plateforme')</p>
                </div>
                
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- Dark Mode Toggle -->
                    <button id="darkModeToggle" class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                        <!-- Sun icon (visible in dark mode) -->
                        <svg id="sunIcon" class="w-5 h-5 sm:w-6 sm:h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <!-- Moon icon (visible in light mode) -->
                        <svg id="moonIcon" class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </button>

                    <!-- Notifications -->
                    <button class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M9 11h.01M9 8h.01M9 5h.01M9 2h.01M15 2h.01M15 5h.01M15 8h.01M15 11h.01M15 14h.01M15 17h.01M15 20h.01"></path>
                        </svg>
                    </button>

                    <!-- User Menu -->
                    <div class="relative">
                        <button id="userMenuButton" class="flex items-center space-x-2 sm:space-x-3 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-medium text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <span class="hidden sm:block text-gray-700 font-medium">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Mon Profil</a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Paramètres</a>
                            <hr class="my-2">
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                    Se déconnecter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 sm:p-6">
            @yield('content')
        </main>
    </div>

    <!-- Notifications Container -->
    <div id="notifications-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
    
    <!-- Session Notifications -->
    @include('components.session-notifications')

    <script>
        // Toggle Sidebar
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        });

        // Mobile Menu Toggle
        document.getElementById('mobileMenuButton').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('mobile-open');
        });

        // User Menu Toggle
        document.getElementById('userMenuButton').addEventListener('click', function() {
            const userMenu = document.getElementById('userMenu');
            userMenu.classList.toggle('hidden');
        });

        // Close user menu when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('userMenu');
            const userMenuButton = document.getElementById('userMenuButton');
            
            if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });

        // Close mobile menu on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.remove('mobile-open');
            }
        });

        // Session management and CSRF token refresh
        let sessionWarningShown = false;
        let sessionTimeout = null;
        
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
                } else if (response.status === 200) {
                    // Session is valid, reset warning
                    sessionWarningShown = false;
                    if (sessionTimeout) {
                        clearTimeout(sessionTimeout);
                    }
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
                    <p class="text-gray-600 mb-6">Votre session a expiré. Veuillez vous reconnecter pour continuer.</p>
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
            initializeDarkMode();
        });
        
        // Dark Mode Functionality
        function initializeDarkMode() {
            const darkModeToggle = document.getElementById('darkModeToggle');
            const sunIcon = document.getElementById('sunIcon');
            const moonIcon = document.getElementById('moonIcon');
            const body = document.body;
            
            // Check for saved dark mode preference or default to light mode
            const isDarkMode = localStorage.getItem('darkMode') === 'true';
            
            // Apply initial theme
            if (isDarkMode) {
                body.classList.add('dark');
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
            } else {
                body.classList.remove('dark');
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
            }
            
            // Toggle dark mode
            darkModeToggle.addEventListener('click', function() {
                const isCurrentlyDark = body.classList.contains('dark');
                
                if (isCurrentlyDark) {
                    // Switch to light mode
                    body.classList.remove('dark');
                    sunIcon.classList.add('hidden');
                    moonIcon.classList.remove('hidden');
                    localStorage.setItem('darkMode', 'false');
                } else {
                    // Switch to dark mode
                    body.classList.add('dark');
                    sunIcon.classList.remove('hidden');
                    moonIcon.classList.add('hidden');
                    localStorage.setItem('darkMode', 'true');
                }
            });
        }
    </script>
    
    <!-- Notifications Service -->
    <script src="{{ asset('js/notifications.js') }}"></script>
</body>
</html>
