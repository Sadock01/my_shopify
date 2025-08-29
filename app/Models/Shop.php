<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'name',
        'slug',
        'domain',
        'description',
        'logo',
        'banner_image',
        'theme_settings',
        'payment_info',
        'contact_email',
        'contact_phone',
        'about_text',
        'social_links',
        'owner_name',
        'owner_email',
        'owner_phone',
        'owner_address',
        'owner_website',
        'owner_bio',
        'is_active'
    ];

    protected $casts = [
        'theme_settings' => 'array',
        'payment_info' => 'array',
        'social_links' => 'array',
        'is_active' => 'boolean'
    ];

    public function template()
    {
        return $this->belongsTo(ShopTemplate::class, 'template_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Obtenir l'URL complète de la boutique
     */
    public function getFullUrlAttribute()
    {
        if ($this->domain) {
            return 'https://' . $this->domain;
        }
        
        return config('app.url') . '/shop/' . $this->slug;
    }

    /**
     * Vérifier si la boutique a un domaine personnalisé
     */
    public function hasCustomDomain()
    {
        return !empty($this->domain);
    }

    /**
     * Vérifier si nous sommes actuellement sur le domaine personnalisé de cette boutique
     */
    public function isOnCustomDomain()
    {
        if (empty($this->domain)) {
            return false;
        }
        
        $currentHost = request()->getHost();
        
        // Ignorer les domaines de développement
        if (in_array($currentHost, ['localhost', '127.0.0.1', 'myshopify.test'])) {
            return false;
        }
        
        return $currentHost === $this->domain || $currentHost === 'www.' . $this->domain;
    }

    /**
     * Scope pour les boutiques actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
