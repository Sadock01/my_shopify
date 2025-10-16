@extends('admin.layout')

@section('title', 'Informations de paiement - ' . $shop->name)

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Informations de paiement - {{ $shop->name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.shops.show', $shop) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.payment-info.update', $shop) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informations bancaires -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 text-purple-600">Informations bancaires</h2>
                
                <div class="space-y-4">
                    <div>
                        <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">Nom de la banque</label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 @error('bank_name') border-red-500 @enderror" 
                               id="bank_name" 
                               name="bank_name" 
                               value="{{ old('bank_name', $shop->bank_name) }}"
                               placeholder="Ex: Crédit Agricole">
                        @error('bank_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="account_holder" class="block text-sm font-medium text-gray-700 mb-2">Titulaire du compte</label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 @error('account_holder') border-red-500 @enderror" 
                               id="account_holder" 
                               name="account_holder" 
                               value="{{ old('account_holder', $shop->account_holder) }}"
                               placeholder="Ex: Jean Dupont">
                        @error('account_holder')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="iban" class="block text-sm font-medium text-gray-700 mb-2">Numéro de compte IBAN</label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 @error('iban') border-red-500 @enderror" 
                               id="iban" 
                               name="iban" 
                               value="{{ old('iban', $shop->iban) }}"
                               placeholder="Ex: FR76 1234 5678 9012 3456 7890 123"
                               maxlength="34">
                        @error('iban')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bic" class="block text-sm font-medium text-gray-700 mb-2">Code BIC/SWIFT</label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 @error('bic') border-red-500 @enderror" 
                               id="bic" 
                               name="bic" 
                               value="{{ old('bic', $shop->bic) }}"
                               placeholder="Ex: AGRIFRPP"
                               maxlength="11">
                        @error('bic')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Méthodes de paiement et instructions -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 text-green-600">Méthodes de paiement acceptées</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Méthodes de paiement</label>
                        <div id="payment-methods-container" class="space-y-2">
                            @if($shop->payment_methods && count($shop->payment_methods) > 0)
                                @foreach($shop->payment_methods as $index => $method)
                                    <div class="flex space-x-2 payment-method-row">
                                        <input type="text" 
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" 
                                               name="payment_methods[]" 
                                               value="{{ $method }}"
                                               placeholder="Ex: Virement bancaire">
                                        <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors remove-payment-method">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex space-x-2 payment-method-row">
                                    <input type="text" 
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" 
                                           name="payment_methods[]" 
                                           value=""
                                           placeholder="Ex: Virement bancaire">
                                    <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors remove-payment-method">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="mt-2 px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors text-sm" id="add-payment-method">
                            <i class="fas fa-plus mr-2"></i> Ajouter une méthode
                        </button>
                    </div>

                    <div>
                        <label for="payment_instructions" class="block text-sm font-medium text-gray-700 mb-2">Instructions de paiement</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('payment_instructions') border-red-500 @enderror" 
                                  id="payment_instructions" 
                                  name="payment_instructions" 
                                  rows="4"
                                  placeholder="Instructions pour les clients sur comment effectuer le paiement...">{{ old('payment_instructions', $shop->payment_instructions) }}</textarea>
                        @error('payment_instructions')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition-colors">
                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
            </button>
        </div>
    </form>

    <!-- Aperçu des informations -->
    <div class="mt-8 bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Aperçu des informations de paiement</h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div>
                <h3 class="text-lg font-medium text-gray-700 mb-4">Informations bancaires</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Banque:</span> {{ $shop->bank_name ?: 'Non renseigné' }}</p>
                    <p><span class="font-medium">Titulaire:</span> {{ $shop->account_holder ?: 'Non renseigné' }}</p>
                    <p><span class="font-medium">IBAN:</span> {{ $shop->iban ?: 'Non renseigné' }}</p>
                    <p><span class="font-medium">BIC:</span> {{ $shop->bic ?: 'Non renseigné' }}</p>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-gray-700 mb-4">Méthodes de paiement</h3>
                @if($shop->payment_methods && count($shop->payment_methods) > 0)
                    <ul class="space-y-1">
                        @foreach($shop->payment_methods as $method)
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span>{{ $method }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">Aucune méthode de paiement définie</p>
                @endif
            </div>
        </div>
        
        @if($shop->payment_instructions)
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-700 mb-4">Instructions de paiement</h3>
                <div class="bg-gray-50 p-4 rounded-md">
                    {!! nl2br(e($shop->payment_instructions)) !!}
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter une méthode de paiement
    document.getElementById('add-payment-method').addEventListener('click', function() {
        const container = document.getElementById('payment-methods-container');
        const newRow = document.createElement('div');
        newRow.className = 'flex space-x-2 payment-method-row';
        newRow.innerHTML = `
            <input type="text" 
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" 
                   name="payment_methods[]" 
                   value=""
                   placeholder="Ex: Virement bancaire">
            <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors remove-payment-method">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(newRow);
    });

    // Supprimer une méthode de paiement
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-payment-method')) {
            const row = e.target.closest('.payment-method-row');
            const container = document.getElementById('payment-methods-container');
            
            // Ne pas supprimer s'il n'y a qu'une seule ligne
            if (container.children.length > 1) {
                row.remove();
            }
        }
    });
});
</script>
@endsection