@extends('admin.layout')

@section('title', 'Gestion des Templates')
@section('page-title', 'Gestion des Templates')
@section('page-description', 'Personnalisez et gérez les templates de vos boutiques')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Templates Disponibles</h2>
    <p class="text-gray-600 mt-2">Choisissez parmi nos templates professionnels pour vos boutiques</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Template Horizon -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
            <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
            </svg>
        </div>
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Horizon Fashion</h3>
            <p class="text-gray-600 text-sm mb-4">Design moderne et élégant pour les boutiques de mode</p>
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Design responsive
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Section Hero impactante
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Carrousel de témoignages
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.templates.preview', 'horizon') }}" 
                   class="flex-1 bg-blue-600 text-white text-center py-2 px-3 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                    Aperçu
                </a>
                <a href="{{ route('admin.templates.customize', 'horizon') }}" 
                   class="flex-1 bg-gray-100 text-gray-700 text-center py-2 px-3 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                    Personnaliser
                </a>
            </div>
        </div>
    </div>

    <!-- Template Tech -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="h-48 bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
            <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
        </div>
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">TechStore</h3>
            <p class="text-gray-600 text-sm mb-4">Style technologique pour les boutiques d'électronique</p>
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Interface futuriste
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Présentation technique
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Design high-tech
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.templates.preview', 'tech') }}" 
                   class="flex-1 bg-blue-600 text-white text-center py-2 px-3 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                    Aperçu
                </a>
                <a href="{{ route('admin.templates.customize', 'tech') }}" 
                   class="flex-1 bg-gray-100 text-gray-700 text-center py-2 px-3 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                    Personnaliser
                </a>
            </div>
        </div>
    </div>

    <!-- Template Luxe -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="h-48 bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center">
            <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
        </div>
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">LuxeMode</h3>
            <p class="text-gray-600 text-sm mb-4">Design premium et sophistiqué pour les boutiques de luxe</p>
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Design sophistiqué
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Présentation premium
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Footer élégant
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.templates.preview', 'luxe') }}" 
                   class="flex-1 bg-blue-600 text-white text-center py-2 px-3 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                    Aperçu
                </a>
                <a href="{{ route('admin.templates.customize', 'luxe') }}" 
                   class="flex-1 bg-gray-100 text-gray-700 text-center py-2 px-3 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                    Personnaliser
                </a>
            </div>
        </div>
    </div>

    <!-- Template Default -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="h-48 bg-gradient-to-br from-gray-500 to-gray-700 flex items-center justify-center">
            <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
            </svg>
        </div>
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Template Par Défaut</h3>
            <p class="text-gray-600 text-sm mb-4">Template classique et polyvalent pour tous types de boutiques</p>
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Design épuré
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Mise en page claire
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Navigation intuitive
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.templates.preview', 'default') }}" 
                   class="flex-1 bg-blue-600 text-white text-center py-2 px-3 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                    Aperçu
                </a>
                <a href="{{ route('admin.templates.customize', 'default') }}" 
                   class="flex-1 bg-gray-100 text-gray-700 text-center py-2 px-3 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                    Personnaliser
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques des Templates -->
<div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Utilisation des Templates</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $shops->where('template', 'horizon')->count() }}</div>
            <div class="text-sm text-gray-600">Horizon Fashion</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-cyan-600">{{ $shops->where('template', 'tech')->count() }}</div>
            <div class="text-sm text-gray-600">TechStore</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-purple-600">{{ $shops->where('template', 'luxe')->count() }}</div>
            <div class="text-sm text-gray-600">LuxeMode</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-gray-600">{{ $shops->where('template', 'default')->count() }}</div>
            <div class="text-sm text-gray-600">Template Par Défaut</div>
        </div>
    </div>
</div>
@endsection
