<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - MyShopify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            letter-spacing: 0.01em;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        }
        
        .glass-effect {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 215, 0, 0.3);
        }
        
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 215, 0, 0.3);
        }
        
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 215, 0, 0.4);
        }
        
        .eye-icon {
            transition: all 0.3s ease;
        }
        
        .eye-icon:hover {
            transform: scale(1.1);
        }
        
        .password-toggle {
            position: relative;
        }
        
        .password-toggle input {
            padding-right: 3rem;
        }
        
        .password-toggle .eye-button {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }
        
        .password-toggle .eye-button:hover {
            background: rgba(0, 0, 0, 0.05);
        }
        
        .input-field {
            position: relative;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,215,0,0.1) 0%, transparent 50%), radial-gradient(circle at 75% 75%, rgba(255,215,0,0.1) 0%, transparent 50%);"></div>
    
    <div class="relative max-w-md w-full space-y-8">
        <!-- Register Card -->
        <div class="glass-effect rounded-2xl p-8 shadow-2xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto h-16 w-16 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mb-4 shadow-lg">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-light text-white mb-2">myShop</h1>
                <h2 class="text-3xl font-semibold text-yellow-400 mb-2">Créer un compte</h2>
                <p class="text-gray-300 text-sm">
                    Ou
                    <a href="{{ route('admin.login') }}" class="font-medium text-yellow-400 hover:text-yellow-300 transition-colors">
                        connectez-vous à votre compte existant
                    </a>
                </p>
            </div>

            @if ($errors->any())
                <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-200">
                                Erreur d'inscription
                            </h3>
                            <div class="mt-2 text-sm text-red-100">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form class="space-y-6" action="{{ route('admin.register.post') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <!-- Name Field -->
                    <div class="input-field">
                        <input id="name" name="name" type="text" autocomplete="name" required 
                               value="{{ old('name') }}"
                               placeholder="Votre nom complet"
                               class="input-focus appearance-none relative block w-full px-4 py-4 bg-white/90 border border-white/30 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all duration-300 sm:text-sm">
                    </div>
                    
                    <!-- Email Field -->
                    <div class="input-field">
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               value="{{ old('email') }}"
                               placeholder="Votre adresse email"
                               class="input-focus appearance-none relative block w-full px-4 py-4 bg-white/90 border border-white/30 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all duration-300 sm:text-sm">
                    </div>
                    
                    <!-- Password Field with Toggle -->
                    <div class="password-toggle">
                        <input id="password" name="password" type="password" autocomplete="new-password" required 
                               placeholder="Votre mot de passe"
                               class="input-focus appearance-none relative block w-full px-4 py-4 bg-white/90 border border-white/30 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all duration-300 sm:text-sm">
                        <button type="button" class="eye-button" onclick="togglePassword()">
                            <svg id="eye-icon" class="h-5 w-5 text-gray-500 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Password Confirmation Field with Toggle -->
                    <div class="password-toggle">
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                               placeholder="Confirmer votre mot de passe"
                               class="input-focus appearance-none relative block w-full px-4 py-4 bg-white/90 border border-white/30 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all duration-300 sm:text-sm">
                        <button type="button" class="eye-button" onclick="togglePasswordConfirmation()">
                            <svg id="eye-icon-confirmation" class="h-5 w-5 text-gray-500 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <p class="text-xs text-gray-300 text-center">Le mot de passe doit contenir au moins 8 caractères.</p>
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required
                           class="h-4 w-4 text-yellow-400 focus:ring-yellow-400 border-white/30 rounded bg-white/90">
                    <label for="terms" class="ml-2 block text-sm text-white">
                        J'accepte les 
                        <a href="#" class="font-medium text-yellow-400 hover:text-yellow-300 transition-colors">conditions d'utilisation</a>
                        et la 
                        <a href="#" class="font-medium text-yellow-400 hover:text-yellow-300 transition-colors">politique de confidentialité</a>
                    </label>
                </div>

                <!-- Create Account Button -->
                <div>
                    <button type="submit" 
                            class="btn-hover group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-medium rounded-xl text-black bg-gradient-to-r from-yellow-400 to-orange-500 hover:from-yellow-500 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400 transition-all duration-300 shadow-lg">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-black/70 group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </span>
                        Créer mon compte
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <div class="text-center mt-6">
                <p class="text-white text-sm">
                    Déjà un compte ? 
                    <a href="{{ route('admin.login') }}" class="font-medium text-yellow-400 hover:text-yellow-300 transition-colors">
                        Se connecter
                    </a>
                </p>
            </div>
        </div>
        
    </div>

    <!-- JavaScript for Password Toggle -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }

        function togglePasswordConfirmation() {
            const passwordInput = document.getElementById('password_confirmation');
            const eyeIcon = document.getElementById('eye-icon-confirmation');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }
    </script>
</body>
</html>
