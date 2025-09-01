@extends('admin.layout')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Témoignages - {{ $shop->name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.shops.testimonials.create', $shop) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                Ajouter un témoignage
            </a>
            <a href="{{ route('admin.shops.manage', $shop) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                Retour à la gestion
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($testimonials->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($testimonials as $testimonial)
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <!-- En-tête avec note et statut -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </div>
                        @if($testimonial->is_featured)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Mis en avant
                            </span>
                        @endif
                    </div>

                    <!-- Commentaire -->
                    <div class="mb-4">
                        <p class="text-gray-700 text-sm leading-relaxed">{{ $testimonial->comment }}</p>
                    </div>

                    <!-- Informations client -->
                    <div class="border-t pt-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $testimonial->customer_name }}</p>
                                @if($testimonial->customer_location)
                                    <p class="text-gray-500 text-xs">{{ $testimonial->customer_location }}</p>
                                @endif
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $testimonial->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="border-t pt-4 mt-4">
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.shops.testimonials.edit', [$shop, $testimonial]) }}" 
                               class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm transition-colors">
                                Modifier
                            </a>
                            <form action="{{ route('admin.shops.testimonials.destroy', [$shop, $testimonial]) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-sm transition-colors"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce témoignage ?')">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $testimonials->links() }}
        </div>
    @else
        <div class="bg-white shadow-md rounded-lg p-6 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun témoignage</h3>
            <p class="mt-1 text-sm text-gray-500">Commencez par ajouter un témoignage pour cette boutique.</p>
            <div class="mt-6">
                <a href="{{ route('admin.shops.testimonials.create', $shop) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    Ajouter un témoignage
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
