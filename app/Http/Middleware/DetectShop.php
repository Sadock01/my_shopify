<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Shop;
use Symfony\Component\HttpFoundation\Response;

class DetectShop
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $domain = $request->getHost();
        
        // Ignorer les domaines de développement
        if (in_array($domain, ['localhost', '127.0.0.1', 'myshopify.test'])) {
            return $next($request);
        }

        // Chercher la boutique par domaine
        $shop = Shop::where('domain', $domain)
            ->orWhere('domain', 'www.' . $domain)
            ->orWhere('domain', str_replace('www.', '', $domain))
            ->active()
            ->first();

        if ($shop) {
            // Ajouter la boutique à la requête pour y accéder dans les contrôleurs
            $request->attributes->set('current_shop', $shop);
            
            // Ajouter la boutique à la session
            session(['current_shop_id' => $shop->id]);
        }

        return $next($request);
    }
}
