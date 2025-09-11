<?php

namespace App\Services;

use App\Models\Shop;
use App\Models\ShopSession;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MultiShopService
{
    /**
     * Connecter un utilisateur à une boutique
     */
    public function connectUserToShop(User $user, Shop $shop): ShopSession
    {
        // Vérifier si une session existe déjà
        $existingSession = ShopSession::where('user_id', $user->id)
            ->where('shop_id', $shop->id)
            ->first();

        if ($existingSession) {
            $existingSession->touchActivity();
            return $existingSession;
        }

        // Créer une nouvelle session
        return ShopSession::create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'session_token' => Str::random(40),
            'cart_data' => [],
            'user_preferences' => [],
            'last_activity' => now(),
        ]);
    }

    /**
     * Obtenir la session active pour une boutique
     */
    public function getActiveSessionForShop(User $user, Shop $shop): ?ShopSession
    {
        return ShopSession::where('user_id', $user->id)
            ->where('shop_id', $shop->id)
            ->where('last_activity', '>', now()->subHours(24))
            ->first();
    }

    /**
     * Obtenir toutes les sessions actives d'un utilisateur
     */
    public function getUserActiveSessions(User $user): \Illuminate\Database\Eloquent\Collection
    {
        return ShopSession::getActiveSessionsForUser($user->id);
    }

    /**
     * Basculer vers une boutique
     */
    public function switchToShop(User $user, Shop $shop): ShopSession
    {
        $session = $this->connectUserToShop($user, $shop);
        
        // Mettre à jour la session Laravel
        session(['current_shop_id' => $shop->id]);
        session(['current_shop_session_token' => $session->session_token]);
        
        return $session;
    }

    /**
     * Obtenir la boutique actuelle de l'utilisateur
     */
    public function getCurrentShop(): ?Shop
    {
        $shopId = session('current_shop_id');
        if (!$shopId) {
            return null;
        }

        return Shop::find($shopId);
    }

    /**
     * Vérifier si l'utilisateur est connecté à une boutique
     */
    public function isUserConnectedToShop(User $user, Shop $shop): bool
    {
        return $this->getActiveSessionForShop($user, $shop) !== null;
    }

    /**
     * Déconnecter un utilisateur d'une boutique
     */
    public function disconnectUserFromShop(User $user, Shop $shop): bool
    {
        $session = $this->getActiveSessionForShop($user, $shop);
        if (!$session) {
            return false;
        }

        $session->delete();
        
        // Si c'était la boutique actuelle, la retirer de la session
        if (session('current_shop_id') == $shop->id) {
            session()->forget(['current_shop_id', 'current_shop_session_token']);
        }

        return true;
    }

    /**
     * Obtenir le panier d'une boutique
     */
    public function getCartForShop(User $user, Shop $shop): array
    {
        $session = $this->getActiveSessionForShop($user, $shop);
        return $session ? $session->cart_data ?? [] : [];
    }

    /**
     * Mettre à jour le panier d'une boutique
     */
    public function updateCartForShop(User $user, Shop $shop, array $cartData): bool
    {
        $session = $this->getActiveSessionForShop($user, $shop);
        if (!$session) {
            return false;
        }

        $session->update([
            'cart_data' => $cartData,
            'last_activity' => now(),
        ]);

        return true;
    }

    /**
     * Nettoyer les sessions expirées
     */
    public function cleanExpiredSessions(): int
    {
        return ShopSession::cleanExpiredSessions();
    }
}
