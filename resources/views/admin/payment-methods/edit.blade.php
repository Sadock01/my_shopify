@extends('admin.layout')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Modifier le Moyen de Paiement - {{ $shop->name }}</h1>
        <a href="{{ route('admin.shops.payment-methods.index', $shop) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
            Retour à la liste
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.shops.payment-methods.update', [$shop, $paymentMethod]) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informations de base -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Informations de Base</h3>
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom du moyen de paiement *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $paymentMethod->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: Virement bancaire, Chèque, Espèces...">
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type de paiement *</label>
                    <select name="type" id="type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner un type</option>
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ old('type', $paymentMethod->type) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Description détaillée du moyen de paiement...">{{ old('description', $paymentMethod->description) }}</textarea>
                </div>
            </div>

            <!-- Configuration et image -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Configuration</h3>
                
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">Icône</label>
                    @if($paymentMethod->icon)
                        <div class="mb-2">
                            <img src="{{ Storage::url($paymentMethod->icon) }}" alt="Icône actuelle" class="w-20 h-20 object-cover rounded">
                        </div>
                    @endif
                    <input type="file" name="icon" id="icon" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Formats acceptés: JPEG, PNG, JPG, WebP. Taille max: 1MB</p>
                </div>

                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Ordre d'affichage</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $paymentMethod->sort_order) }}" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="0">
                    <p class="text-xs text-gray-500 mt-1">Laissez vide pour ajouter à la fin</p>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Moyen de paiement actif</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Détails spécifiques selon le type -->
        <div class="mt-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Détails Spécifiques</h3>
            
            <div id="bank-transfer-details" class="hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">Nom de la banque</label>
                        <input type="text" name="details[bank_name]" value="{{ old('details.bank_name', $paymentMethod->details['bank_name'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Ex: Banque Populaire">
                    </div>
                    <div>
                        <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">Numéro de compte</label>
                        <input type="text" name="details[account_number]" value="{{ old('details.account_number', $paymentMethod->details['account_number'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Ex: FR76 1234 5678 9012 3456 7890 123">
                    </div>
                    <div>
                        <label for="swift_code" class="block text-sm font-medium text-gray-700 mb-2">Code SWIFT/BIC</label>
                        <input type="text" name="details[swift_code]" value="{{ old('details.swift_code', $paymentMethod->details['swift_code'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Ex: BAPPFR22XXX">
                    </div>
                    <div>
                        <label for="account_holder" class="block text-sm font-medium text-gray-700 mb-2">Titulaire du compte</label>
                        <input type="text" name="details[account_holder]" value="{{ old('details.account_holder', $paymentMethod->details['account_holder'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Ex: Jean Dupont">
                    </div>
                </div>
            </div>

            <div id="check-details" class="hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="check_recipient" class="block text-sm font-medium text-gray-700 mb-2">Destinataire du chèque</label>
                        <input type="text" name="details[check_recipient]" value="{{ old('details.check_recipient', $paymentMethod->details['check_recipient'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Ex: Jean Dupont">
                    </div>
                    <div>
                        <label for="check_address" class="block text-sm font-medium text-gray-700 mb-2">Adresse d'envoi</label>
                        <textarea name="details[check_address]" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Adresse pour envoyer le chèque...">{{ old('details.check_address', $paymentMethod->details['check_address'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div id="mobile-money-details" class="hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="mobile_provider" class="block text-sm font-medium text-gray-700 mb-2">Fournisseur</label>
                        <input type="text" name="details[mobile_provider]" value="{{ old('details.mobile_provider', $paymentMethod->details['mobile_provider'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Ex: Orange Money, MTN Mobile Money...">
                    </div>
                    <div>
                        <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-2">Numéro de téléphone</label>
                        <input type="text" name="details[mobile_number]" value="{{ old('details.mobile_number', $paymentMethod->details['mobile_number'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Ex: +33 7 84 95 05 84">
                    </div>
                </div>
            </div>

            <div id="other-details" class="hidden">
                <div>
                    <label for="custom_details" class="block text-sm font-medium text-gray-700 mb-2">Détails personnalisés</label>
                    <textarea name="details[custom_details]" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Détails spécifiques pour ce moyen de paiement...">{{ old('details.custom_details', $paymentMethod->details['custom_details'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('admin.shops.payment-methods.index', $shop) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                Annuler
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                Mettre à jour
            </button>
        </div>
    </form>
</div>

<script>
// Afficher/masquer les détails selon le type sélectionné
document.getElementById('type').addEventListener('change', function() {
    const type = this.value;
    
    // Masquer tous les détails
    document.querySelectorAll('[id$="-details"]').forEach(el => el.classList.add('hidden'));
    
    // Afficher les détails correspondants
    if (type === 'bank_transfer') {
        document.getElementById('bank-transfer-details').classList.remove('hidden');
    } else if (type === 'check') {
        document.getElementById('check-details').classList.remove('hidden');
    } else if (type === 'mobile_money') {
        document.getElementById('mobile-money-details').classList.remove('hidden');
    } else if (type === 'other') {
        document.getElementById('other-details').classList.remove('hidden');
    }
});

// Déclencher l'événement au chargement si un type est déjà sélectionné
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    if (typeSelect.value) {
        typeSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
