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
                'name' => 'Horizon Modern',
                'slug' => 'horizon-modern',
                'description' => 'Template moderne et épuré inspiré de la marque Horizon, parfait pour les boutiques de mode contemporaine.',
                'preview_image' => '/templates/horizon-modern.jpg',
                'theme_colors' => [
                    'primary' => '#000000',
                    'secondary' => '#ffffff',
                    'accent' => '#f8f9fa',
                    'text' => '#333333',
                    'text_light' => '#666666'
                ],
                'layout_options' => [
                    'header_style' => 'minimal',
                    'product_grid' => '4-columns',
                    'show_newsletter' => true,
                    'show_social_links' => true,
                    'footer_style' => 'simple'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Classic E-commerce',
                'slug' => 'classic-ecommerce',
                'description' => 'Template classique pour e-commerce avec une mise en page traditionnelle et fonctionnelle.',
                'preview_image' => '/templates/classic-ecommerce.jpg',
                'theme_colors' => [
                    'primary' => '#2c3e50',
                    'secondary' => '#ecf0f1',
                    'accent' => '#3498db',
                    'text' => '#2c3e50',
                    'text_light' => '#7f8c8d'
                ],
                'layout_options' => [
                    'header_style' => 'classic',
                    'product_grid' => '3-columns',
                    'show_newsletter' => true,
                    'show_social_links' => true,
                    'footer_style' => 'detailed'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Minimalist Fashion',
                'slug' => 'minimalist-fashion',
                'description' => 'Template minimaliste spécialement conçu pour les boutiques de mode avec un design épuré.',
                'preview_image' => '/templates/minimalist-fashion.jpg',
                'theme_colors' => [
                    'primary' => '#ffffff',
                    'secondary' => '#f5f5f5',
                    'accent' => '#000000',
                    'text' => '#000000',
                    'text_light' => '#999999'
                ],
                'layout_options' => [
                    'header_style' => 'minimal',
                    'product_grid' => '2-columns',
                    'show_newsletter' => false,
                    'show_social_links' => true,
                    'footer_style' => 'minimal'
                ],
                'is_active' => true
            ]
        ];

        foreach ($templates as $template) {
            ShopTemplate::create($template);
        }
    }
}
