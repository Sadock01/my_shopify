@extends('admin.layout')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Ajouter un Produit - {{ $shop->name }}</h1>
        <a href="{{ route('admin.shops.products.index', $shop) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
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

    <form action="{{ route('admin.shops.products.store', $shop) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informations de base -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Informations de Base</h3>
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom du produit *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: T-shirt en coton, iPhone 15...">
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Catégorie *</label>
                    <select name="category_id" id="category_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description" id="description" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Description détaillée du produit...">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Prix et stock -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Prix et Stock</h3>
                
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Prix de vente *</label>
                    <div class="relative">
                        <input type="number" name="price" id="price" value="{{ old('price') }}" required step="0.01" min="0"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0.00">
                        <span class="absolute left-3 top-2 text-gray-500">€</span>
                    </div>
                </div>

                <div>
                    <label for="original_price" class="block text-sm font-medium text-gray-700 mb-2">Prix original</label>
                    <div class="relative">
                        <input type="number" name="original_price" id="original_price" value="{{ old('original_price') }}" step="0.01" min="0"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0.00">
                        <span class="absolute left-3 top-2 text-gray-500">€</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Laissez vide si pas de réduction</p>
                </div>

                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stock disponible *</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock') }}" required min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="0">
                </div>
            </div>
        </div>

        <!-- Images -->
        <div class="mt-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Images</h3>
            
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Image principale *</label>
                <input type="file" name="image" id="image" accept="image/*" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Formats acceptés: JPEG, PNG, JPG, WebP. Taille max: 2MB</p>
            </div>

            <div>
                <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Images supplémentaires</label>
                <input type="file" name="images[]" id="images" accept="image/*" multiple
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Vous pouvez sélectionner plusieurs images</p>
            </div>
        </div>

        <!-- Variantes -->
        <div class="mt-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Variantes</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="sizes" class="block text-sm font-medium text-gray-700 mb-2">Tailles disponibles</label>
                    <input type="text" name="sizes[]" value="{{ old('sizes.0') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2"
                           placeholder="Ex: S, M, L, XL">
                    <input type="text" name="sizes[]" value="{{ old('sizes.1') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2"
                           placeholder="Ex: 38, 40, 42, 44">
                    <input type="text" name="sizes[]" value="{{ old('sizes.2') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: 128GB, 256GB, 512GB">
                    <p class="text-xs text-gray-500 mt-1">Une taille par ligne, laissez vide si pas de tailles</p>
                </div>

                <div>
                    <label for="colors" class="block text-sm font-medium text-gray-700 mb-2">Couleurs disponibles</label>
                    <input type="text" name="colors[]" value="{{ old('colors.0') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2"
                           placeholder="Ex: Rouge, Bleu, Vert">
                    <input type="text" name="colors[]" value="{{ old('colors.1') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2"
                           placeholder="Ex: Noir, Blanc, Gris">
                    <input type="text" name="colors[]" value="{{ old('colors.2') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: Or, Argent, Rose">
                    <p class="text-xs text-gray-500 mt-1">Une couleur par ligne, laissez vide si pas de couleurs</p>
                </div>
            </div>
        </div>

        <!-- Options -->
        <div class="mt-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Options</h3>
            
            <div class="flex space-x-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Produit mis en avant</span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Produit actif</span>
                </label>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('admin.shops.products.index', $shop) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                Annuler
            </a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                Créer le produit
            </button>
        </div>
    </form>
</div>
@endsection
