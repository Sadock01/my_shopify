<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - {{ $shop->name ?? 'Boutique' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: 'Montserrat', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .input-focus:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        .eye-icon {
            transition: all 0.3s ease;
        }
        .password-toggle:hover .eye-icon {
            transform: scale(1.1);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo de la boutique -->
        <div class="text-center mb-8">
            @if(isset($shop) && $shop->logo)
                <img src="{{ Storage::url($shop->logo) }}" alt="{{ $shop->name }}" class="w-20 h-20 mx-auto mb-4 rounded-full object-cover shadow-lg">
            @else
                <div class="w-20 h-20 mx-auto mb-4 bg-white rounded-full flex items-center justify-center shadow-lg">
                    <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            @endif
            <h1 class="text-3xl font-bold text-white mb-2">{{ $shop->name ?? 'Boutique' }}</h1>
            <p class="text-white/80">Créez votre compte</p>
        </div>

        <!-- Formulaire d'inscription -->
        <div class="glass-effect rounded-2xl p-8 shadow-2xl">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('shop.register.slug.post', ['shop' => $shop->slug]) }}" class="space-y-6">
                @csrf
                
                <div>
                    <input type="text" 
                           name="name" 
                           placeholder="Nom complet"
                           value="{{ old('name') }}"
                           class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/70 input-focus transition-all duration-300"
                           required>
                </div>

                <div>
                    <input type="email" 
                           name="email" 
                           placeholder="Adresse email"
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/70 input-focus transition-all duration-300"
                           required>
                </div>

                <div class="relative">
                    <input type="password" 
                           name="password" 
                           id="password"
                           placeholder="Mot de passe"
                           class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/70 input-focus transition-all duration-300 pr-12"
                           required>
                    <button type="button" 
                            onclick="togglePassword('password')"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 password-toggle">
                        <svg id="eye-open-password" class="w-6 h-6 text-white/70 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg id="eye-closed-password" class="w-6 h-6 text-white/70 eye-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                        </svg>
                    </button>
                </div>

                <div class="relative">
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation"
                           placeholder="Confirmer le mot de passe"
                           class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/70 input-focus transition-all duration-300 pr-12"
                           required>
                    <button type="button" 
                            onclick="togglePassword('password_confirmation')"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 password-toggle">
                        <svg id="eye-open-confirmation" class="w-6 h-6 text-white/70 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg id="eye-closed-confirmation" class="w-6 h-6 text-white/70 eye-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                        </svg>
                    </button>
                </div>

                <button type="submit" 
                        class="w-full bg-white text-gray-800 py-3 px-6 rounded-lg font-semibold btn-hover transition-all duration-300 shadow-lg">
                    Créer mon compte
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-white/70 text-sm">
                    Déjà un compte ? 
                    <a href="{{ route('shop.login.slug', ['shop' => $shop->slug]) }}" class="text-white font-semibold hover:underline">
                        Se connecter
                    </a>
                </p>
            </div>

            @if(isset($shop))
            <div class="mt-4 text-center">
                <a href="{{ route('shop.home.slug', ['shop' => $shop->slug]) }}" 
                   class="text-white/70 hover:text-white text-sm transition-colors">
                    ← Retour à la boutique
                </a>
            </div>
            @endif
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const eyeOpen = document.getElementById(`eye-open-${fieldId}`);
            const eyeClosed = document.getElementById(`eye-closed-${fieldId}`);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
