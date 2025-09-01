@extends('admin.layout')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Ajouter un Témoignage - {{ $shop->name }}</h1>
        <a href="{{ route('admin.shops.testimonials.index', $shop) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
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

    <form action="{{ route('admin.shops.testimonials.store', $shop) }}" method="POST" class="bg-white shadow-md rounded-lg p-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informations client -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Informations Client</h3>
                
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">Nom du client *</label>
                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: Jean Dupont, Marie Martin...">
                </div>

                <div>
                    <label for="customer_location" class="block text-sm font-medium text-gray-700 mb-2">Localisation du client</label>
                    <input type="text" name="customer_location" id="customer_location" value="{{ old('customer_location') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: Paris, Lyon, Marseille...">
                </div>
            </div>

            <!-- Évaluation et options -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Évaluation</h3>
                
                <div>
                    <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Note *</label>
                    <select name="rating" id="rating" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner une note</option>
                        <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1 étoile - Très déçu</option>
                        <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2 étoiles - Déçu</option>
                        <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3 étoiles - Moyen</option>
                        <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4 étoiles - Satisfait</option>
                        <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>5 étoiles - Très satisfait</option>
                    </select>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Mettre en avant ce témoignage</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Les témoignages mis en avant apparaîtront en priorité</p>
                </div>
            </div>
        </div>

        <!-- Commentaire -->
        <div class="mt-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Commentaire</h3>
            
            <div>
                <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Témoignage *</label>
                <textarea name="comment" id="comment" rows="6" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Rédigez le témoignage du client...">{{ old('comment') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Minimum 10 caractères. Soyez authentique et détaillé.</p>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('admin.shops.testimonials.index', $shop) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                Annuler
            </a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                Créer le témoignage
            </button>
        </div>
    </form>
</div>
@endsection
