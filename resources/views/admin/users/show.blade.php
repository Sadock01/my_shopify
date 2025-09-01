@extends('admin.layout')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Détails de l'Utilisateur</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                Modifier
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profil utilisateur -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Profil Utilisateur</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom complet</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->phone ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type de compte</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_admin ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $user->is_admin ? 'Administrateur' : 'Client' }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date d'inscription</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Adresse -->
            @if($user->address || $user->city || $user->postal_code || $user->country)
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Adresse</h2>
                <div class="space-y-2">
                    @if($user->address)
                        <p class="text-sm text-gray-900"><strong>Adresse :</strong> {{ $user->address }}</p>
                    @endif
                    @if($user->city)
                        <p class="text-sm text-gray-900"><strong>Ville :</strong> {{ $user->city }}</p>
                    @endif
                    @if($user->postal_code)
                        <p class="text-sm text-gray-900"><strong>Code postal :</strong> {{ $user->postal_code }}</p>
                    @endif
                    @if($user->country)
                        <p class="text-sm text-gray-900"><strong>Pays :</strong> {{ $user->country }}</p>
                    @endif
                </div>
            </div>
            @endif

            <!-- Commandes récentes -->
            @if($user->orders->count() > 0)
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Commandes Récentes</h2>
                <div class="space-y-3">
                    @foreach($user->orders as $order)
                    <div class="border border-gray-200 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Commande #{{ $order->id }}</p>
                                <p class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ number_format($order->total_amount, 2) }}€</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($user->orders->count() >= 10)
                    <div class="mt-4 text-center">
                        <a href="#" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                            Voir toutes les commandes
                        </a>
                    </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar avec statistiques -->
        <div class="space-y-6">
            <!-- Statistiques -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistiques</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total commandes</span>
                        <span class="text-lg font-semibold text-gray-900">{{ $user->orders_count }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Dernière commande</span>
                        <span class="text-sm text-gray-900">
                            {{ $user->orders->first() ? $user->orders->first()->created_at->format('d/m/Y') : 'Aucune' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Membre depuis</span>
                        <span class="text-sm text-gray-900">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors text-center block">
                        Modifier le profil
                    </a>
                    
                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full {{ $user->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-lg transition-colors">
                            {{ $user->is_active ? 'Désactiver' : 'Activer' }} le compte
                        </button>
                    </form>

                    @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')">
                                Supprimer le compte
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Informations système -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations Système</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    <p><strong>ID :</strong> {{ $user->id }}</p>
                    <p><strong>Email vérifié :</strong> {{ $user->email_verified_at ? 'Oui' : 'Non' }}</p>
                    <p><strong>Dernière connexion :</strong> {{ $user->last_login_at ?? 'Jamais' }}</p>
                    <p><strong>Mise à jour :</strong> {{ $user->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
