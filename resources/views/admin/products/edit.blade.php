@extends('admin.layout')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Modifier le Produit - {{ $shop->name }}</h1>
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

    <form action="{{ route('admin.shops.products.update', [$shop, $product]) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informations de base -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Informations de Base</h3>
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom du produit *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: T-shirt en coton, iPhone 15...">
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Catégorie *</label>
                    <select name="category_id" id="category_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description" id="description" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Description détaillée du produit...">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            <!-- Prix et stock -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Prix et Stock</h3>
                
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Prix de vente *</label>
                    <div class="relative">
                        <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" required step="0.01" min="0"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0.00">
                        <span class="absolute left-3 top-2 text-gray-500">€</span>
                    </div>
                </div>

                <div>
                    <label for="original_price" class="block text-sm font-medium text-gray-700 mb-2">Prix original</label>
                    <div class="relative">
                        <input type="number" name="original_price" id="original_price" value="{{ old('original_price', $product->original_price) }}" step="0.01" min="0"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0.00">
                        <span class="absolute left-3 top-2 text-gray-500">€</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Laissez vide si pas de réduction</p>
                </div>

                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stock disponible *</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="0">
                </div>
            </div>
        </div>

        <!-- Images -->
        <div class="mt-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Images</h3>
            
            <!-- Image principale actuelle -->
            @if($product->image)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image principale actuelle</label>
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('documents/' . $product->image) }}" alt="{{ $product->name }}" class="w-20 h-20 object-cover rounded-lg border">
                        <div>
                            <p class="text-sm text-gray-600">Image actuelle</p>
                            <p class="text-xs text-gray-500">Sélectionnez une nouvelle image pour la remplacer</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $product->image ? 'Nouvelle image principale' : 'Image principale *' }}
                </label>
                <input type="file" name="image" id="image" accept="image/*" {{ !$product->image ? 'required' : '' }}
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Formats acceptés: JPEG, PNG, JPG, WebP. Taille max: 2MB</p>
            </div>

            <!-- Images supplémentaires actuelles -->
            @if($product->images && count($product->images) > 0)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Images supplémentaires actuelles</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($product->images as $image)
                            <div class="relative">
                                <img src="{{ asset('documents/' . $image) }}" alt="{{ $product->name }}" class="w-full h-20 object-cover rounded-lg border">
                                <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                    <span class="text-white text-xs">Image {{ $loop->iteration }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Sélectionnez de nouvelles images pour remplacer toutes les images actuelles</p>
                </div>
            @endif

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
                    @php
                        $sizes = old('sizes', $product->sizes ?? []);
                    @endphp
                    <input type="text" name="sizes[]" value="{{ $sizes[0] ?? '' }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2"
                           placeholder="Ex: S, M, L, XL">
                    <input type="text" name="sizes[]" value="{{ $sizes[1] ?? '' }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2"
                           placeholder="Ex: 38, 40, 42, 44">
                    <input type="text" name="sizes[]" value="{{ $sizes[2] ?? '' }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: 128GB, 256GB, 512GB">
                    <p class="text-xs text-gray-500 mt-1">Une taille par ligne, laissez vide si pas de tailles</p>
                </div>

                <div>
                    <label for="colors" class="block text-sm font-medium text-gray-700 mb-2">Couleurs disponibles</label>
                    @php
                        $colors = old('colors', $product->colors ?? []);
                    @endphp
                    <input type="text" name="colors[]" value="{{ $colors[0] ?? '' }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2"
                           placeholder="Ex: Rouge, Bleu, Vert">
                    <input type="text" name="colors[]" value="{{ $colors[1] ?? '' }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2"
                           placeholder="Ex: Noir, Blanc, Gris">
                    <input type="text" name="colors[]" value="{{ $colors[2] ?? '' }}"
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
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Produit mis en avant</span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
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
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                Mettre à jour le produit
            </button>
        </div>
    </form>
</div>
@endsection
