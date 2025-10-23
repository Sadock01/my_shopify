@extends('shop.layout')

@section('title', 'Informations de paiement')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Informations de paiement</h1>
            <p class="text-lg text-gray-600">Votre commande a été créée avec succès !</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Commande confirmée</h2>
                <p class="text-gray-600">Merci pour votre commande. Veuillez effectuer le virement bancaire selon les informations ci-dessous.</p>
            </div>

            <!-- Payment Information -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations bancaires</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Banque</label>
                        <p class="text-gray-900 font-medium">{{ $shop->bank_name ?: 'Non renseigné' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Titulaire du compte</label>
                        <p class="text-gray-900 font-medium">{{ $shop->account_holder ?: 'Non renseigné' }}</p>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Numéro de compte IBAN</label>
                        <div class="flex items-center space-x-2">
                            <p class="text-gray-900 font-mono text-lg">{{ $shop->iban ?: 'Non renseigné' }}</p>
                            @if($shop->iban)
                                <button onclick="copyToClipboard('{{ $shop->iban }}')" class="text-primary hover:text-primary-dark">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    @if($shop->bic)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Code BIC/SWIFT</label>
                        <p class="text-gray-900 font-mono">{{ $shop->bic }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Méthodes de paiement acceptées -->
            @if($shop->payment_methods && count($shop->payment_methods) > 0)
            <div class="bg-green-50 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-green-900 mb-4">Méthodes de paiement acceptées</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($shop->payment_methods as $method)
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-green-800 font-medium">{{ $method }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Instructions de paiement personnalisées -->
            @if($shop->payment_instructions)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-yellow-900 mb-4">Instructions de paiement</h3>
                <div class="text-yellow-800">
                    {!! nl2br(e($shop->payment_instructions)) !!}
                </div>
            </div>
            @endif

            <!-- Important Notes -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">Informations importantes</h3>
                <ul class="space-y-2 text-blue-800">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Veuillez inclure votre nom et le numéro de commande dans la référence du virement</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Le traitement de votre commande commencera après réception du paiement</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Vous recevrez un email de confirmation une fois le paiement reçu</span>
                    </li>
                </ul>
            </div>

            <!-- Contact Information -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Besoin d'aide ?</h3>
                <p class="text-gray-600 mb-4">Si vous avez des questions concernant votre commande ou le paiement, n'hésitez pas à nous contacter :</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($shop->contact_email)
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <a href="mailto:{{ $shop->contact_email }}" class="text-primary hover:underline">{{ $shop->contact_email }}</a>
                    </div>
                    @endif
                    
                    @if($shop->contact_phone)
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
            <div class="mt-8 flex flex-col sm:flex-row gap-4">
                <a href="{{ route('shop.home') }}" class="flex-1 bg-gray-100 text-gray-900 py-3 px-6 rounded-md font-semibold hover:bg-gray-200 transition-colors text-center">
                    Annuler
                </a>
                <a href="/upload-payment-proof/{{ $shop->slug }}" class="flex-1 bg-green-600 text-white py-3 px-6 rounded-md font-semibold hover:bg-green-700 transition-colors text-center">
                    Payer l'achat
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function copyToClipboard(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Numéro de compte copié dans le presse-papiers !');
            });
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('Numéro de compte copié dans le presse-papiers !');
        }
    }
</script>
@endpush 