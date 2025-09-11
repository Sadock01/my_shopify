@php
    use App\Services\MultiShopService;
    $multiShopService = app(MultiShopService::class);
    $connectedShops = Auth::check() ? $multiShopService->getUserActiveSessions(Auth::user()) : collect();
@endphp

@if($connectedShops->count() > 1)
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
    <h3 class="text-sm font-medium text-blue-900 mb-3">Boutiques connectées</h3>
    <div class="flex flex-wrap gap-2">
        @foreach($connectedShops as $session)
            <div class="flex items-center space-x-2 bg-white rounded-lg px-3 py-2 border border-blue-200">
                @if($session->shop->logo)
                    <img src="{{ Storage::url($session->shop->logo) }}" 
                         alt="{{ $session->shop->name }}" 
                         class="w-6 h-6 rounded-full object-cover">
                @else
                    <div class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center">
                        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                @endif
                <span class="text-sm font-medium text-gray-900">{{ $session->shop->name }}</span>
                @if(session('current_shop_id') == $session->shop->id)
                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Actuelle</span>
                @else
                    <form method="POST" action="{{ route('shop.switch.slug', ['shop' => $session->shop->slug]) }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full hover:bg-blue-200 transition-colors">
                            Basculer
                        </button>
                    </form>
                @endif
                <form method="POST" action="{{ route('shop.logout.specific.slug', ['shop' => $session->shop->slug]) }}" class="inline">
                    @csrf
                    <button type="submit" class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full hover:bg-red-200 transition-colors">
                        Déconnecter
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endif
