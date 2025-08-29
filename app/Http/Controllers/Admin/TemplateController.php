<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopTemplate;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = ShopTemplate::all();
        $shops = Shop::with('template')->get();
        
        return view('admin.templates.index', compact('templates', 'shops'));
    }

    public function preview($template)
    {
        $templateData = $this->getTemplateData($template);
        
        return view('admin.templates.preview', compact('template', 'templateData'));
    }

    public function customize($template)
    {
        $templateData = $this->getTemplateData($template);
        
        return view('admin.templates.customize', compact('template', 'templateData'));
    }

    private function getTemplateData($template)
    {
        $templates = [
            'horizon' => [
                'name' => 'Horizon Fashion',
                'description' => 'Template moderne et élégant pour les boutiques de mode',
                'features' => [
                    'Design responsive et moderne',
                    'Section Hero impactante',
                    'Grille de produits élégante',
                    'Carrousel de témoignages',
                    'Footer compact et professionnel'
                ],
                'colors' => ['#1f2937', '#3b82f6', '#f3f4f6'],
                'preview_image' => '/images/templates/horizon-preview.jpg'
            ],
            'tech' => [
                'name' => 'TechStore',
                'description' => 'Template technologique pour les boutiques d\'électronique',
                'features' => [
                    'Interface futuriste',
                    'Présentation technique des produits',
                    'Comparaison de spécifications',
                    'Section avis techniques',
                    'Design high-tech'
                ],
                'colors' => ['#0f172a', '#06b6d4', '#64748b'],
                'preview_image' => '/images/templates/tech-preview.jpg'
            ],
            'luxe' => [
                'name' => 'LuxeMode',
                'description' => 'Template premium pour les boutiques de luxe',
                'features' => [
                    'Design sophistiqué et raffiné',
                    'Présentation premium des produits',
                    'Galerie d\'images haute qualité',
                    'Section témoignages VIP',
                    'Footer élégant et discret'
                ],
                'colors' => ['#1c1917', '#a855f7', '#fafaf9'],
                'preview_image' => '/images/templates/luxe-preview.jpg'
            ],
            'default' => [
                'name' => 'Template Par Défaut',
                'description' => 'Template classique et polyvalent',
                'features' => [
                    'Design épuré et professionnel',
                    'Mise en page claire et simple',
                    'Navigation intuitive',
                    'Présentation des produits standard',
                    'Footer informatif'
                ],
                'colors' => ['#374151', '#6b7280', '#f9fafb'],
                'preview_image' => '/images/templates/default-preview.jpg'
            ]
        ];

        return $templates[$template] ?? $templates['default'];
    }
}
