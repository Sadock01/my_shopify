@extends('admin.layout')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Modifier la Boutique</h1>
        <a href="{{ route('admin.shops.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
            Retour à la liste
        </a>
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

    <form action="{{ route('admin.shops.update', $shop) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informations de base -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Informations de Base</h3>
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom de la boutique *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $shop->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug *</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $shop->slug) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $shop->description) }}</textarea>
                </div>

                <div>
                    <label for="template" class="block text-sm font-medium text-gray-700 mb-2">Template</label>
                    <select name="template" id="template" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Template par défaut</option>
                        @foreach($templates as $template)
                            <option value="{{ $template->slug }}" {{ old('template', $shop->template->slug ?? '') == $template->slug ? 'selected' : '' }}>
                                {{ $template->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Images -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Images</h3>
                
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                    @if($shop->logo)
                        <div class="mb-2">
                            <img src="{{ Storage::url($shop->logo) }}" alt="Logo actuel" class="w-20 h-20 object-cover rounded">
                        </div>
                    @endif
                    <input type="file" name="logo" id="logo" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="favicon" class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                    @if($shop->favicon)
                        <div class="mb-2">
                            <img src="{{ Storage::url($shop->favicon) }}" alt="Favicon actuel" class="w-8 h-8 object-cover rounded">
                            <p class="text-xs text-gray-500 mt-1">Favicon actuel (16x16 ou 32x32 px recommandé)</p>
                        </div>
                    @endif
                    <input type="file" name="favicon" id="favicon" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Format recommandé : .ico, .png, .jpg (16x16 ou 32x32 pixels)</p>
                </div>

                <div>
                    <label for="banner" class="block text-sm font-medium text-gray-700 mb-2">Bannière</label>
                    @if($shop->banner_image)
                        <div class="mb-2">
                            <img src="{{ Storage::url($shop->banner_image) }}" alt="Bannière actuelle" class="w-full h-32 object-cover rounded">
                        </div>
                    @endif
                    <input type="file" name="banner" id="banner" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Informations du propriétaire -->
        <div class="mt-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Informations du Propriétaire</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="owner_name" class="block text-sm font-medium text-gray-700 mb-2">Nom du propriétaire</label>
                    <input type="text" name="owner_name" id="owner_name" value="{{ old('owner_name', $shop->owner_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="owner_email" class="block text-sm font-medium text-gray-700 mb-2">Email du propriétaire</label>
                    <input type="email" name="owner_email" id="owner_email" value="{{ old('owner_email', $shop->owner_email) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="owner_phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone du propriétaire</label>
                    <input type="text" name="owner_phone" id="owner_phone" value="{{ old('owner_phone', $shop->owner_phone) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="owner_website" class="block text-sm font-medium text-gray-700 mb-2">Site web du propriétaire</label>
                    <input type="url" name="owner_website" id="owner_website" value="{{ old('owner_website', $shop->owner_website) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Informations de contact -->
        <div class="mt-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Informations de Contact</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Email de contact</label>
                    <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $shop->contact_email) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone de contact</label>
                    <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $shop->contact_phone) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Statut -->
        <div class="mt-6">
            <label class="flex items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $shop->is_active) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-700">Boutique active</span>
            </label>
        </div>

        <!-- Boutons d'action -->
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('admin.shops.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                Annuler
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                Mettre à jour
            </button>
        </div>
    </form>
</div>

<script>
// Auto-génération du slug
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
        .replace(/[^a-z0-9 -]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    document.getElementById('slug').value = slug;
});
</script>
@endsection
