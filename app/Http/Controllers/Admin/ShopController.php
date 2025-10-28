<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Category;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::withCount(['products', 'orders'])->latest()->get();
        return view('admin.shops.index', compact('shops'));
    }

    public function create()
    {
        $templates = [
            'horizon' => 'Horizon Fashion (Moderne & Élégant)',
            'tech' => 'TechStore (Technologie)',
            'luxe' => 'LuxeMode (Luxe & Premium)',
            'default' => 'Template Par Défaut (Classique)'
        ];
        
        return view('admin.shops.create', compact('templates'));
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'slug' => 'required|string|unique:shops|max:255',
    //         'description' => 'required|string',
    //         'template' => 'required|string|in:horizon,tech,luxe,default',
    //         'logo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
    //         'banner' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
    //         'owner_name' => 'required|string|max:255',
    //         'owner_email' => 'required|email',
    //         'owner_phone' => 'nullable|string|max:20',
    //         'owner_website' => 'nullable|url',
    //         'contact_email' => 'required|email',
    //         'contact_phone' => 'nullable|string|max:20',
    //         'is_active' => 'boolean'
    //     ]);

    //     // Récupérer le template par défaut si aucun n'est sélectionné
    //     if (!isset($validated['template']) || empty($validated['template'])) {
    //         $defaultTemplate = \App\Models\ShopTemplate::where('slug', 'default')->first();
    //         if (!$defaultTemplate) {
    //             // Créer un template par défaut si il n'existe pas
    //             $defaultTemplate = \App\Models\ShopTemplate::create([
    //                 'name' => 'Template Par Défaut',
    //                 'slug' => 'default',
    //                 'description' => 'Template classique et polyvalent',
    //                 'preview_image' => 'default-preview.jpg',
    //                 'theme_colors' => json_encode(['primary' => '#6B7280', 'secondary' => '#9CA3AF']),
    //                 'layout_options' => json_encode(['basic' => true]),
    //                 'is_active' => true
    //             ]);
    //         }
    //         $validated['template_id'] = $defaultTemplate->id;
    //     } else {
    //         // Récupérer le template sélectionné
    //         $selectedTemplate = \App\Models\ShopTemplate::where('slug', $validated['template'])->first();
    //         if ($selectedTemplate) {
    //             $validated['template_id'] = $selectedTemplate->id;
    //         } else {
    //             // Fallback sur le template par défaut
    //             $defaultTemplate = \App\Models\ShopTemplate::where('slug', 'default')->first();
    //             $validated['template_id'] = $defaultTemplate->id;
    //         }
    //     }

    //     // Ajouter les champs JSON obligatoires
    //     $validated['theme_settings'] = json_encode([
    //         'primary_color' => '#3B82F6',
    //         'secondary_color' => '#8B5CF6',
    //         'accent_color' => '#F59E0B',
    //         'text_color' => '#1F2937',
    //         'background_color' => '#FFFFFF',
    //         'font_family' => 'Inter, sans-serif'
    //     ]);

    //     $validated['payment_info'] = json_encode([
    //         'bank_name' => 'Banque Populaire',
    //         'account_number' => 'FR76 1234 5678 9012 3456 7890 123',
    //         'swift_code' => 'BAPPFR22XXX',
    //         'payment_methods' => ['Virement bancaire', 'Chèque', 'Espèces']
    //     ]);

    //     // Ajouter les champs manquants
    //     $validated['banner_image'] = null;
    //     $validated['domain'] = null;
    //     $validated['about_text'] = null;
    //     $validated['social_links'] = json_encode([
    //         'facebook' => null,
    //         'twitter' => null,
    //         'instagram' => null,
    //         'linkedin' => null
    //     ]);

    //     // Créer la boutique
    //     $shop = Shop::create($validated);

    //     // Upload des images
    //     $this->uploadImages($shop, $request);

    //     // Créer les catégories par défaut
    //     $this->createDefaultCategories($shop);

    //     // Créer quelques avis par défaut pour la pub
    //     $this->createDefaultTestimonials($shop);

    //     return redirect()->route('admin.shops.index')
    //         ->with('success', 'Boutique "' . $shop->name . '" créée avec succès !');
    // }
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:shops,slug|max:255',
            'description' => 'required|string',
            'template' => 'required|string',
            'logo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // 2MB max
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,webp,ico|max:1024', // 1MB max
            'banner' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255',
            'owner_phone' => 'nullable|string|max:20',
            'owner_website' => 'nullable|url|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);
    
        try {
            DB::beginTransaction();
    
            // Récupérer le template sélectionné ou utiliser le template par défaut
            $templateSlug = $validated['template'] ?? 'default';
            $template = \App\Models\ShopTemplate::where('slug', $templateSlug)->first();
            
            if (!$template) {
                // Si le template n'existe pas, utiliser le template par défaut
                $template = \App\Models\ShopTemplate::where('slug', 'default')->first();
                
                if (!$template) {
                    // Créer le template par défaut s'il n'existe pas
                    $template = \App\Models\ShopTemplate::create([
                        'name' => 'Template Par Défaut',
                        'slug' => 'default',
                        'description' => 'Template classique et polyvalent',
                        'is_active' => true
                    ]);
                }
            }
    
            // Créer la boutique
            $shop = Shop::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'template_id' => $template->id,
                'owner_name' => $validated['owner_name'],
                'owner_email' => $validated['owner_email'],
                'owner_phone' => $validated['owner_phone'],
                'owner_website' => $validated['owner_website'],
                'contact_email' => $validated['contact_email'],
                'contact_phone' => $validated['contact_phone'],
                'is_active' => $validated['is_active'] ?? true,
                // Champs JSON obligatoires
                'theme_settings' => json_encode([
                    'primary_color' => '#3B82F6',
                    'secondary_color' => '#8B5CF6',
                    'accent_color' => '#F59E0B',
                    'text_color' => '#1F2937',
                    'background_color' => '#FFFFFF',
                    'font_family' => 'Montserrat, sans-serif'
                ]),
                'payment_info' => json_encode([
                    'bank_name' => 'Banque Populaire',
                    'account_number' => 'FR76 1234 5678 9012 3456 7890 123',
                    'swift_code' => 'BAPPFR22XXX',
                    'payment_methods' => ['Virement bancaire', 'Chèque', 'Espèces']
                ]),
                'social_links' => json_encode([
                    'facebook' => null,
                    'twitter' => null,
                    'instagram' => null,
                    'linkedin' => null
                ])
            ]);
    
            // Upload des images
            $destinationPath = public_path('documents/shops/' . $shop->slug);
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            if ($request->hasFile('logo')) {
                $filename = uniqid() . '.' . $request->file('logo')->getClientOriginalExtension();
                $request->file('logo')->move($destinationPath, $filename);
                $logoPath = 'shops/' . $shop->slug . '/' . $filename;
                $shop->update(['logo' => $logoPath]);
            }
    
            if ($request->hasFile('favicon')) {
                $filename = uniqid() . '.' . $request->file('favicon')->getClientOriginalExtension();
                $request->file('favicon')->move($destinationPath, $filename);
                $faviconPath = 'shops/' . $shop->slug . '/' . $filename;
                $shop->update(['favicon' => $faviconPath]);
            }
    
            if ($request->hasFile('banner')) {
                $filename = uniqid() . '.' . $request->file('banner')->getClientOriginalExtension();
                $request->file('banner')->move($destinationPath, $filename);
                $bannerPath = 'shops/' . $shop->slug . '/' . $filename;
                $shop->update(['banner_image' => $bannerPath]);
            }
    
            // L'admin créera ses propres catégories via l'interface d'administration
            
            // Ne pas créer de témoignages par défaut - utiliser ceux créés par l'utilisateur
    
            DB::commit();
    
            return redirect()->route('admin.shops.index')
                ->with('success', 'Boutique créée avec succès !');
    
        } catch (\Exception $e) {
            DB::rollback();
            
            // Supprimer les images uploadées en cas d'erreur
            if (isset($logoPath)) {
                $imagePath = public_path('documents/' . $logoPath);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            if (isset($faviconPath)) {
                $imagePath = public_path('documents/' . $faviconPath);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            if (isset($bannerPath)) {
                $imagePath = public_path('documents/' . $bannerPath);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
    
            return back()->withInput()
                ->with('error', 'Erreur lors de la création de la boutique: ' . $e->getMessage());
        }
    }
    public function edit(Shop $shop)
    {
        $templates = \App\Models\ShopTemplate::where('is_active', true)->get();
        
        return view('admin.shops.edit', compact('shop', 'templates'));
    }

    public function update(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:shops,slug,' . $shop->id . '|max:255',
            'description' => 'required|string',
            'template' => 'required|string|in:horizon,tech,luxe,default',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,webp,ico|max:1024',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email',
            'owner_phone' => 'nullable|string|max:20',
            'owner_website' => 'nullable|url',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);

        // Mettre à jour la boutique
        $shop->update($validated);

        // Upload des nouvelles images si fournies
        if ($request->hasFile('logo') || $request->hasFile('favicon') || $request->hasFile('banner')) {
            $this->uploadImages($shop, $request);
        }

        return redirect()->route('admin.shops.index')
            ->with('success', 'Boutique "' . $shop->name . '" mise à jour !');
    }

    public function destroy(Shop $shop)
    {
        // Supprimer les images
        if ($shop->logo) {
            $imagePath = public_path('documents/' . $shop->logo);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        if ($shop->favicon) {
            $imagePath = public_path('documents/' . $shop->favicon);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        if ($shop->banner_image) {
            $imagePath = public_path('documents/' . $shop->banner_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $shop->delete();

        return redirect()->route('admin.shops.index')
            ->with('success', 'Boutique supprimée avec succès !');
    }

    public function toggleStatus(Shop $shop)
    {
        $shop->update(['is_active' => !$shop->is_active]);
        
        $status = $shop->is_active ? 'activée' : 'désactivée';
        
        return response()->json([
            'success' => true,
            'message' => "Boutique {$status} avec succès !",
            'is_active' => $shop->is_active
        ]);
    }

    public function manage(Shop $shop)
    {
        $shop->load([
            'products' => function($query) {
                $query->latest()->take(6);
            },
            'categories',
            'testimonials'
        ]);
        
        // Ajouter les compteurs
        $shop->products_count = $shop->products()->count();
        $shop->orders_count = $shop->orders()->count();
        $shop->categories_count = $shop->categories()->count();
        
        return view('admin.shops.manage', compact('shop'));
    }

    private function uploadImages($shop, $request)
    {
        $destinationPath = public_path('documents/shops/' . $shop->slug);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Logo
        if ($request->hasFile('logo')) {
            if ($shop->logo) {
                $oldImagePath = public_path('documents/' . $shop->logo);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $filename = uniqid() . '.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move($destinationPath, $filename);
            $logoPath = 'shops/' . $shop->slug . '/' . $filename;
            $shop->update(['logo' => $logoPath]);
        }

        // Favicon
        if ($request->hasFile('favicon')) {
            if ($shop->favicon) {
                $oldImagePath = public_path('documents/' . $shop->favicon);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $filename = uniqid() . '.' . $request->file('favicon')->getClientOriginalExtension();
            $request->file('favicon')->move($destinationPath, $filename);
            $faviconPath = 'shops/' . $shop->slug . '/' . $filename;
            $shop->update(['favicon' => $faviconPath]);
        }

        // Bannière
        if ($request->hasFile('banner')) {
            if ($shop->banner_image) {
                $oldImagePath = public_path('documents/' . $shop->banner_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $filename = uniqid() . '.' . $request->file('banner')->getClientOriginalExtension();
            $request->file('banner')->move($destinationPath, $filename);
            $bannerPath = 'shops/' . $shop->slug . '/' . $filename;
            $shop->update(['banner_image' => $bannerPath]);
        }
    }


    private function createDefaultTestimonials($shop)
    {
        $defaultTestimonials = [
            [
                'customer_name' => 'Marie Dupont',
                'rating' => 5,
                'comment' => 'Excellente boutique ! Les produits sont de qualité et la livraison est rapide.',
                'is_featured' => true
            ],
            [
                'customer_name' => 'Jean Martin',
                'rating' => 5,
                'comment' => 'Service client impeccable et produits conformes à mes attentes. Je recommande !',
                'is_featured' => true
            ],
            [
                'customer_name' => 'Sophie Bernard',
                'rating' => 4,
                'comment' => 'Très satisfaite de mes achats. Prix compétitifs et qualité au rendez-vous.',
                'is_featured' => false
            ]
        ];

        foreach ($defaultTestimonials as $testimonial) {
            $shop->testimonials()->create($testimonial);
        }
    }
}
