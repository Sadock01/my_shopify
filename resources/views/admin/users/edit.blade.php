@extends('admin.layout')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Modifier l'Utilisateur</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.show', $user) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                Voir le profil
            </a>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                Retour à la liste
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white shadow-md rounded-lg p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informations de base -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Informations de Base</h3>
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom complet *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: Jean Dupont">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: jean.dupont@email.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe</label>
                    <input type="password" name="password" id="password"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Laissez vide pour ne pas changer">
                    <p class="text-xs text-gray-500 mt-1">Laissez vide pour conserver le mot de passe actuel</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Retapez le nouveau mot de passe">
                </div>
            </div>

            <!-- Contact et adresse -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Contact et Adresse</h3>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: +33 6 12 34 56 78">
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                    <textarea name="address" id="address" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Ex: 123 Rue de la Paix">{{ old('address', $user->address) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                        <input type="text" name="city" id="city" value="{{ old('city', $user->city) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Ex: Paris">
                    </div>
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Code postal</label>
                        <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $user->postal_code) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Ex: 75001">
                    </div>
                </div>

                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Pays</label>
                    <input type="text" name="country" id="country" value="{{ old('country', $user->country) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: France">
                </div>
            </div>
        </div>

        <!-- Options du compte -->
        <div class="mt-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Options du Compte</h3>
            
            <div class="flex space-x-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_admin" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                           {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                    <span class="ml-2 text-sm text-gray-700 {{ $user->id === auth()->id() ? 'text-gray-400' : '' }}">
                        Accorder les droits d'administrateur
                        @if($user->id === auth()->id())
                            <span class="text-xs text-gray-500 block">(Vous ne pouvez pas modifier vos propres droits)</span>
                        @endif
                    </span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                           {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                    <span class="ml-2 text-sm text-gray-700 {{ $user->id === auth()->id() ? 'text-gray-400' : '' }}">
                        Compte actif
                        @if($user->id === auth()->id())
                            <span class="text-xs text-gray-500 block">(Vous ne pouvez pas désactiver votre propre compte)</span>
                        @endif
                    </span>
                </label>
            </div>
            
            @if($user->id === auth()->id())
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div class="text-sm text-yellow-700">
                            <p class="font-medium">Attention :</p>
                            <p>Vous modifiez votre propre profil. Certaines options sont désactivées pour des raisons de sécurité.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-blue-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-sm text-blue-700">
                            <p class="font-medium">Note importante :</p>
                            <p>Les utilisateurs avec des droits d'administrateur auront accès à toutes les fonctionnalités de la plateforme. Utilisez cette option avec précaution.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Informations système -->
        <div class="mt-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Informations Système</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                <div>
                    <span class="font-medium">ID utilisateur :</span> {{ $user->id }}
                </div>
                <div>
                    <span class="font-medium">Inscrit le :</span> {{ $user->created_at->format('d/m/Y à H:i') }}
                </div>
                <div>
                    <span class="font-medium">Dernière mise à jour :</span> {{ $user->updated_at->format('d/m/Y à H:i') }}
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('admin.users.show', $user) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                Annuler
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection
