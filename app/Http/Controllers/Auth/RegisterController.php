<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Shop;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        // Rediriger vers la première boutique active pour faire des commandes
        $activeShop = Shop::active()->first();
        
        if ($activeShop) {
            return redirect()->route('shop.home.slug', ['shop' => $activeShop->slug])
                ->with('success', 'Compte créé avec succès ! Bienvenue ! Vous pouvez maintenant faire vos commandes.');
        }
        
        // Si aucune boutique active, rediriger vers la page d'accueil
        return redirect('/')->with('success', 'Compte créé avec succès ! Bienvenue !');
    }
}
