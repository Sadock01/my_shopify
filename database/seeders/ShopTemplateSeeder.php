<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShopTemplate;

class ShopTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Horizon Fashion',
                'slug' => 'horizon',
                'description' => 'Design moderne et élégant pour les boutiques de mode',
                'preview_image' => 'horizon-preview.jpg',
                'theme_colors' => json_encode([
                    'primary' => '#3B82F6',
                    'secondary' => '#8B5CF6',
                    'accent' => '#F59E0B',
                    'background' => '#FFFFFF',
                    'text' => '#1F2937'
                ]),
                'layout_options' => json_encode([
                    'hero_section' => true,
                    'testimonials_carousel' => true,
                    'featured_products' => true,
                    'payment_methods' => true
                ]),
                'is_active' => true
            ],
            [
                'name' => 'TechStore',
                'slug' => 'tech',
                'description' => 'Style technologique pour les boutiques d\'électronique',
                'preview_image' => 'tech-preview.jpg',
                'theme_colors' => json_encode([
                    'primary' => '#06B6D4',
                    'secondary' => '#3B82F6',
                    'accent' => '#10B981',
                    'background' => '#FFFFFF',
                    'text' => '#0F172A'
                ]),
                'layout_options' => json_encode([
                    'hero_section' => true,
                    'tech_features' => true,
                    'product_specs' => true,
                    'support_info' => true
                ]),
                'is_active' => true
            ],
            [
                'name' => 'LuxeMode',
                'slug' => 'luxe',
                'description' => 'Design premium et sophistiqué pour les boutiques de luxe',
                'preview_image' => 'luxe-preview.jpg',
                'theme_colors' => json_encode([
                    'primary' => '#8B5CF6',
                    'secondary' => '#EC4899',
                    'accent' => '#F59E0B',
                    'background' => '#FFFFFF',
                    'text' => '#111827'
                ]),
                'layout_options' => json_encode([
                    'hero_section' => true,
                    'luxury_features' => true,
                    'premium_content' => true,
                    'elegant_footer' => true
                ]),
                'is_active' => true
            ],
            [
                'name' => 'Template Par Défaut',
                'slug' => 'default',
                'description' => 'Template classique et polyvalent pour tous types de boutiques',
                'preview_image' => 'default-preview.jpg',
                'theme_colors' => json_encode([
                    'primary' => '#6B7280',
                    'secondary' => '#9CA3AF',
                    'accent' => '#F59E0B',
                    'background' => '#FFFFFF',
                    'text' => '#374151'
                ]),
                'layout_options' => json_encode([
                    'hero_section' => true,
                    'basic_layout' => true,
                    'simple_navigation' => true,
                    'clean_footer' => true
                ]),
                'is_active' => true
            ]
        ];

        foreach ($templates as $template) {
            ShopTemplate::create($template);
        }

        $this->command->info('4 templates de boutique ont été créés avec succès !');
    }
}
