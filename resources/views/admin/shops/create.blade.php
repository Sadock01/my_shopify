@extends('admin.layout')

@section('title', 'Créer une Boutique')
@section('page-title', 'Créer une Boutique')
@section('page-description', 'Ajouter une nouvelle boutique à votre plateforme')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Informations de la Boutique</h2>
            <p class="text-gray-600 mt-1">Remplissez les informations de base pour créer votre nouvelle boutique</p>
        </div>

        <form method="POST" action="{{ route('admin.shops.store') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <!-- Informations de Base -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nom de la Boutique *
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ex: Horizon Fashion"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                        Slug/URL *
                    </label>
                    <input type="text" 
                           id="slug" 
                           name="slug" 
                           value="{{ old('slug') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ex: horizon-fashion"
                           required>
                    <p class="mt-1 text-sm text-gray-500">L'URL de votre boutique sera : /shop/horizon-fashion</p>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-8">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description *
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Décrivez votre boutique..."
                          required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sélection du Template -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-4">
                    Choisir un Template *
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($templates as $key => $template)
                    <label class="relative cursor-pointer">
                        <input type="radio" 
                               name="template" 
                               value="{{ $key }}" 
                               class="sr-only"
                               {{ old('template') == $key ? 'checked' : '' }}
                               {{ $loop->first ? 'checked' : '' }}
                               required>
                        <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors template-option">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full flex items-center justify-center">
                                    <div class="w-2 h-2 bg-blue-600 rounded-full hidden radio-dot"></div>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $template }}</h3>
                                    @if($key === 'horizon')
                                        <p class="text-sm text-gray-600">Design moderne et élégant</p>
                                    @elseif($key === 'tech')
                                        <p class="text-sm text-gray-600">Style technologique</p>
                                    @elseif($key === 'luxe')
                                        <p class="text-sm text-gray-600">Design premium et sophistiqué</p>
                                    @else
                                        <p class="text-sm text-gray-600">Template classique et polyvalent</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('template')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Images -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                        Logo de la Boutique *
                    </label>
                    <input type="file" 
                           id="logo" 
                           name="logo" 
                           accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, WebP. Max: 2MB</p>
                    @error('logo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="favicon" class="block text-sm font-medium text-gray-700 mb-2">
                        Favicon
                    </label>
                    <input type="file" 
                           id="favicon" 
                           name="favicon" 
                           accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-sm text-gray-500">Format: .ico, .png, .jpg (16x16 ou 32x32 px)</p>
                    @error('favicon')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="banner" class="block text-sm font-medium text-gray-700 mb-2">
                        Bannière de la Boutique *
                    </label>
                    <input type="file" 
                           id="banner" 
                           name="banner" 
                           accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, WebP. Max: 5MB</p>
                    @error('banner')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Informations du Fondateur -->
            <div class="border-t border-gray-200 pt-8 mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations du Fondateur</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="owner_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom du Fondateur *
                        </label>
                        <input type="text" 
                               id="owner_name" 
                               name="owner_name" 
                               value="{{ old('owner_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ex: Jean Dupont"
                               required>
                        @error('owner_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="owner_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email du Fondateur *
                        </label>
                        <input type="email" 
                               id="owner_email" 
                               name="owner_email" 
                               value="{{ old('owner_email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ex: jean@example.com"
                               required>
                        @error('owner_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="owner_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Téléphone du Fondateur
                        </label>
                        <input type="tel" 
                               id="owner_phone" 
                               name="owner_phone" 
                               value="{{ old('owner_phone') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ex: +33 1 23 45 67 89">
                        @error('owner_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="owner_website" class="block text-sm font-medium text-gray-700 mb-2">
                            Site Web du Fondateur
                        </label>
                        <input type="url" 
                               id="owner_website" 
                               name="owner_website" 
                               value="{{ old('owner_website') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ex: https://jeandupont.com">
                        @error('owner_website')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informations de Contact -->
            <div class="border-t border-gray-200 pt-8 mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de Contact</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email de Contact *
                        </label>
                        <input type="email" 
                               id="contact_email" 
                               name="contact_email" 
                               value="{{ old('contact_email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ex: contact@boutique.com"
                               required>
                        @error('contact_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Téléphone de Contact
                        </label>
                        <input type="tel" 
                               id="contact_phone" 
                               name="contact_phone" 
                               value="{{ old('contact_phone') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ex: +33 1 23 45 67 90">
                        @error('contact_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Statut -->
            <div class="border-t border-gray-200 pt-8 mb-8">
                <div class="flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">
                        Activer la boutique immédiatement
                    </label>
                </div>
                <p class="mt-1 text-sm text-gray-500">Si désactivé, la boutique ne sera pas visible publiquement</p>
            </div>

            <!-- Boutons d'Action -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('admin.shops.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-colors">
                    Créer la Boutique
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.template-option {
    transition: all 0.2s ease-in-out;
    cursor: pointer;
}

.template-option:hover {
    border-color: #93C5FD;
    background-color: #F8FAFC;
}

.template-option.selected {
    border-color: #3B82F6;
    background-color: #EFF6FF;
}

.radio-dot {
    transition: all 0.2s ease-in-out;
}
</style>

<script>
// Auto-génération du slug
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slug = name
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    
    document.getElementById('slug').value = slug;
});

// Gestion des templates
document.querySelectorAll('input[name="template"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Retirer la sélection de tous les templates
        document.querySelectorAll('.template-option').forEach(option => {
            option.classList.remove('border-blue-500', 'bg-blue-50');
        });
        
        // Ajouter la sélection au template choisi
        if (this.checked) {
            this.closest('.template-option').classList.add('border-blue-500', 'bg-blue-50');
        }
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const templateOptions = document.querySelectorAll('input[name="template"]');
    const templateLabels = document.querySelectorAll('.template-option');
    const radioDots = document.querySelectorAll('.radio-dot');

    // Fonction pour mettre à jour la sélection visuelle
    function updateSelection() {
        templateOptions.forEach((option, index) => {
            const label = templateLabels[index];
            const dot = radioDots[index];
            
            if (option.checked) {
                label.classList.remove('border-gray-200');
                label.classList.add('border-blue-500', 'bg-blue-50');
                dot.classList.remove('hidden');
            } else {
                label.classList.remove('border-blue-500', 'bg-blue-50');
                label.classList.add('border-gray-200');
                dot.classList.add('hidden');
            }
        });
    }

    // Écouter les changements de sélection
    templateOptions.forEach(option => {
        option.addEventListener('change', updateSelection);
    });

    // Initialiser la sélection
    updateSelection();

    // Ajouter des événements de clic sur les labels
    templateLabels.forEach((label, index) => {
        label.addEventListener('click', () => {
            templateOptions[index].checked = true;
            updateSelection();
        });
    });
});
</script>

@endsection
