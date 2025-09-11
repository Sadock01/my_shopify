<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Shop;

class LoginController extends Controller
{
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

            // Rediriger les utilisateurs vers la première boutique active pour faire des commandes
            $activeShop = Shop::active()->first();
            
            if ($activeShop) {
                return redirect()->intended(route('shop.home.slug', ['shop' => $activeShop->slug]))
                    ->with('success', 'Connexion réussie ! Vous pouvez maintenant faire vos commandes.');
            }
            
            // Si aucune boutique active, rediriger vers la page d'accueil
            return redirect()->intended('/')->with('success', 'Connexion réussie !');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        // Vérifier si l'utilisateur est connecté avant de le déconnecter
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login')->with('success', 'Déconnexion réussie !');
        }
        
        // Si l'utilisateur n'est pas connecté, rediriger vers la page de login admin
        return redirect()->route('admin.login')->with('info', 'Vous n\'étiez pas connecté.');
    }
}
