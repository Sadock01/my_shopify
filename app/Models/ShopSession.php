<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_id',
        'session_token',
        'cart_data',
        'user_preferences',
        'last_activity',
    ];

    protected $casts = [
        'cart_data' => 'array',
        'user_preferences' => 'array',
        'last_activity' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la boutique
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Mettre à jour l'activité
     */
    public function touchActivity(): void
    {
        $this->update(['last_activity' => now()]);
    }

    /**
     * Obtenir les sessions actives d'un utilisateur
     */
    public static function getActiveSessionsForUser(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('user_id', $userId)
            ->where('last_activity', '>', now()->subHours(24))
            ->with('shop')
            ->orderBy('last_activity', 'desc')
            ->get();
    }

    /**
     * Nettoyer les sessions expirées
     */
    public static function cleanExpiredSessions(): int
    {
        return static::where('last_activity', '<', now()->subHours(24))->delete();
    }
}
