@props(['type' => 'success', 'message' => '', 'duration' => 8000])

<div class="notification fixed top-4 right-4 z-50 transform translate-x-full transition-all duration-500 ease-in-out" 
     data-duration="{{ $duration }}"
     x-data="{ show: false }"
     x-init="
         show = true;
         setTimeout(() => {
             show = false;
             setTimeout(() => $el.remove(), 500);
         }, {{ $duration }});
     "
     x-show="show"
     x-transition:enter="transition ease-out duration-500"
     x-transition:enter-start="transform translate-x-full opacity-0"
     x-transition:enter-end="transform translate-x-0 opacity-100"
     x-transition:leave="transition ease-in duration-500"
     x-transition:leave-start="transform translate-x-0 opacity-100"
     x-transition:leave-end="transform translate-x-full opacity-0">
    
    <div class="bg-white rounded-lg shadow-2xl border-l-4 min-w-80 max-w-96 overflow-hidden
                @if($type === 'success') border-green-500 @endif
                @if($type === 'error') border-red-500 @endif
                @if($type === 'warning') border-yellow-500 @endif
                @if($type === 'info') border-blue-500 @endif">
        
        <div class="flex items-start p-4">
            <!-- Icône -->
            <div class="flex-shrink-0 mr-3">
                @if($type === 'success')
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                @elseif($type === 'error')
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                @elseif($type === 'warning')
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                @else
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                @endif
            </div>
            
            <!-- Contenu -->
            <div class="flex-1 min-w-0">
                <h4 class="text-sm font-medium text-gray-900 mb-1">
                    @if($type === 'success') Succès @endif
                    @if($type === 'error') Erreur @endif
                    @if($type === 'warning') Attention @endif
                    @if($type === 'info') Information @endif
                </h4>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $message }}</p>
            </div>
            
            <!-- Bouton de fermeture -->
            <div class="flex-shrink-0 ml-3">
                <button @click="show = false; setTimeout(() => $el.remove(), 500)" 
                        class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Barre de progression -->
        <div class="h-1 bg-gray-200">
            <div class="h-full bg-gradient-to-r 
                        @if($type === 'success') from-green-500 to-green-400 @endif
                        @if($type === 'error') from-red-500 to-red-400 @endif
                        @if($type === 'warning') from-yellow-500 to-yellow-400 @endif
                        @if($type === 'info') from-blue-500 to-blue-400 @endif
                        transition-all duration-{{ $duration }} ease-linear"
                 style="width: 0%"
                 x-init="
                     setTimeout(() => {
                         $el.style.width = '100%';
                     }, 100);
                 "></div>
        </div>
    </div>
</div>

