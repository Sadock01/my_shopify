@extends('shop.layout')

@section('title', 'Paiement confirmé')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Paiement confirmé !</h1>
            <p class="text-lg text-gray-600">Votre preuve de paiement a été envoyée avec succès</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Merci pour votre commande !</h2>
                <p class="text-gray-600 mb-6">Nous avons bien reçu votre preuve de paiement. Votre commande sera traitée dans les plus brefs délais.</p>
            </div>

            <!-- Prochaines étapes -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">Prochaines étapes</h3>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</div>
                        <div>
                            <p class="font-medium text-blue-900">Vérification du paiement</p>
                            <p class="text-sm text-blue-700">Nous vérifions votre preuve de paiement (24-48h)</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">2</div>
                        <div>
                            <p class="font-medium text-gray-600">Préparation de la commande</p>
                            <p class="text-sm text-gray-500">Votre commande sera préparée après validation</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">3</div>
                        <div>
                            <p class="font-medium text-gray-600">Expédition</p>
                            <p class="text-sm text-gray-500">Vous recevrez un email de confirmation d'expédition</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations de contact -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Besoin d'aide ?</h3>
                <p class="text-gray-600 mb-4">Si vous avez des questions concernant votre commande, n'hésitez pas à nous contacter :</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <a href="mailto:{{ $shop->contact_email ?? 'contact@example.com' }}" class="text-primary hover:underline">
                            {{ $shop->contact_email ?? 'contact@example.com' }}
                        </a>
                    </div>
                    
                    @if($shop->contact_phone ?? false)
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <a href="tel:{{ $shop->contact_phone }}" class="text-primary hover:underline">{{ $shop->contact_phone }}</a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('shop.home.slug', ['shop' => $shop->slug]) }}" class="flex-1 bg-gray-100 text-gray-900 py-3 px-6 rounded-md font-semibold hover:bg-gray-200 transition-colors text-center">
                    Retour à l'accueil
                </a>
                <a href="{{ route('shop.products.slug', ['shop' => $shop->slug]) }}" class="flex-1 bg-primary text-white py-3 px-6 rounded-md font-semibold hover:opacity-90 transition-opacity text-center">
                    Continuer les achats
                </a>
            </div>
        </div>
    </div>
@endsection
