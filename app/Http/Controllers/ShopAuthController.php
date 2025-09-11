<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use App\Services\MultiShopService;

class ShopAuthController extends Controller
{
    protected $multiShopService;

    public function __construct(MultiShopService $multiShopService)
    {
        $this->multiShopService = $multiShopService;
    }

    /**
     * Afficher la page de connexion avec le template de la boutique
     */
    public function showLogin(Request $request)
    {
        $shop = $this->getCurrentShop($request);
        $template = $this->getShopTemplate($shop);
        
        return view("auth.templates.{$template}.login", compact('shop'));
    }

    /**
     * Afficher la page d'inscription avec le template de la boutique
     */
    public function showRegister(Request $request)
    {
        $shop = $this->getCurrentShop($request);
        $template = $this->getShopTemplate($shop);
        
        return view("auth.templates.{$template}.register", compact('shop'));
    }

    /**
     * Traiter la connexion
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Rediriger les admins vers le dashboard admin
            if (Auth::user()->is_admin) {
                return redirect()->intended('/admin/dashboard')->with('success', 'Connexion admin réussie !');
            }

            // Connecter l'utilisateur à la boutique avec le système multi-boutiques
            $shop = $this->getCurrentShop($request);
            if ($shop) {
                $this->multiShopService->switchToShop(Auth::user(), $shop);
                return redirect()->intended(route('shop.home.slug', ['shop' => $shop->slug]))
                    ->with('success', 'Connexion réussie ! Vous êtes maintenant connecté à ' . $shop->name);
            }
            
            // Si aucune boutique, rediriger vers la page d'accueil
            return redirect()->intended('/')->with('success', 'Connexion réussie !');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->withInput($request->only('email'));
    }

    /**
     * Traiter l'inscription
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
        ]);

        Auth::login($user);

        // Connecter l'utilisateur à la boutique avec le système multi-boutiques
        $shop = $this->getCurrentShop($request);
        if ($shop) {
            $this->multiShopService->switchToShop(Auth::user(), $shop);
            return redirect()->route('shop.home.slug', ['shop' => $shop->slug])
                ->with('success', 'Compte créé avec succès ! Bienvenue ! Vous êtes maintenant connecté à ' . $shop->name);
        }
        
        // Si aucune boutique, rediriger vers la page d'accueil
        return redirect('/')->with('success', 'Compte créé avec succès ! Bienvenue !');
    }

    /**
     * Basculer vers une boutique
     */
    public function switchShop(Request $request, Shop $targetShop)
    {
        if (!Auth::check()) {
            return redirect()->route('shop.login.slug', ['shop' => $targetShop->slug])
                ->with('error', 'Vous devez être connecté pour accéder à cette boutique.');
        }

        $this->multiShopService->switchToShop(Auth::user(), $targetShop);
        
        return redirect()->route('shop.home.slug', ['shop' => $targetShop->slug])
            ->with('success', "Vous êtes maintenant connecté à {$targetShop->name}");
    }

    /**
     * Déconnexion d'une boutique spécifique
     */
    public function logoutFromShop(Request $request, Shop $targetShop)
    {
        if (!Auth::check()) {
            return redirect()->route('shop.login.slug', ['shop' => $targetShop->slug]);
        }

        $this->multiShopService->disconnectUserFromShop(Auth::user(), $targetShop);
        
        return redirect()->route('shop.home.slug', ['shop' => $targetShop->slug])
            ->with('success', "Vous avez été déconnecté de {$targetShop->name}");
    }

    /**
     * Déconnexion complète
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            // Déconnecter de toutes les boutiques
            $this->multiShopService->getUserActiveSessions(Auth::user())->each->delete();
            
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login')->with('success', 'Déconnexion réussie !');
        }
        
        return redirect()->route('admin.login')->with('info', 'Vous n\'étiez pas connecté.');
    }

    /**
     * Obtenir la boutique actuelle
     */
    private function getCurrentShop(Request $request)
    {
        // Vérifier si on est dans une boutique via slug
        if ($request->route('shop')) {
            $shop = $request->route('shop');
            // Si c'est une chaîne (slug), chercher la boutique
            if (is_string($shop)) {
                return Shop::where('slug', $shop)->first();
            }
            // Si c'est déjà un objet Shop, le retourner
            return $shop;
        }

        // Vérifier si on est dans une boutique via domaine
        if ($request->attributes->has('current_shop')) {
            return $request->attributes->get('current_shop');
        }

        // Vérifier la session
        if (session('current_shop_id')) {
            return Shop::find(session('current_shop_id'));
        }

        // Retourner la première boutique active par défaut
        return Shop::active()->first();
    }

    /**
     * Obtenir le template de la boutique
     */
    private function getShopTemplate($shop)
    {
        if (!$shop || !is_object($shop)) {
            return 'default';
        }

        // Charger la relation template si elle n'est pas déjà chargée
        if (!$shop->relationLoaded('template')) {
            $shop->load('template');
        }

        // Vérifier si la boutique a un template associé
        if ($shop->template && isset($shop->template->slug)) {
            return $shop->template->slug;
        }

        // Template par défaut
        return 'default';
    }
}
