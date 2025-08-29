<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shop;
use App\Models\Category;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des catégories
        $categories = [
            ['name' => 'Vêtements', 'slug' => 'vetements', 'sort_order' => 1],
            ['name' => 'Chaussures', 'slug' => 'chaussures', 'sort_order' => 2],
            ['name' => 'Accessoires', 'slug' => 'accessoires', 'sort_order' => 3],
            ['name' => 'Électronique', 'slug' => 'electronique', 'sort_order' => 4],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Créer des boutiques
        $shops = [
            [
                'template_id' => 1, // Horizon Modern
                'name' => 'Horizon Fashion',
                'slug' => 'horizon-fashion',
                'domain' => 'horizon-fashion.com',
                'description' => 'Boutique de mode moderne et élégante',
                'logo' => '/shops/horizon/logo.png',
                'banner_image' => '/shops/horizon/banner.jpg',
                'theme_settings' => [
                    'primary_color' => '#000000',
                    'secondary_color' => '#ffffff',
                    'accent_color' => '#f8f9fa',
                    'font_family' => 'Inter',
                    'header_style' => 'minimal'
                ],
                'payment_info' => [
                    'bank_name' => 'Banque Populaire',
                    'account_number' => 'FR1234567890123456789012345',
                    'account_holder' => 'Horizon Fashion SARL',
                    'swift_code' => 'BPPBFRPP'
                ],
                'contact_email' => 'contact@horizon-fashion.com',
                'contact_phone' => '+33 1 23 45 67 89',
                'about_text' => 'Horizon Fashion est une marque de mode contemporaine qui allie élégance et confort.',
                'social_links' => [
                    'facebook' => 'https://facebook.com/horizonfashion',
                    'instagram' => 'https://instagram.com/horizonfashion',
                    'twitter' => 'https://twitter.com/horizonfashion'
                ],
                'owner_name' => 'Marie Dubois',
                'owner_email' => 'marie.dubois@horizon-fashion.com',
                'owner_phone' => '+33 6 12 34 56 78',
                'owner_address' => '123 Avenue des Champs-Élysées, 75008 Paris, France',
                'owner_website' => 'https://marie-dubois.com',
                'owner_bio' => 'Fondatrice de Horizon Fashion, passionnée de mode depuis plus de 15 ans. Mon objectif est de créer des vêtements élégants et confortables pour tous.',
                'is_active' => true
            ],
            [
                'template_id' => 2, // Classic E-commerce
                'name' => 'TechStore',
                'slug' => 'techstore',
                'domain' => 'techstore.fr',
                'description' => 'Boutique d\'électronique et gadgets',
                'logo' => '/shops/techstore/logo.png',
                'banner_image' => '/shops/techstore/banner.jpg',
                'theme_settings' => [
                    'primary_color' => '#2c3e50',
                    'secondary_color' => '#ecf0f1',
                    'accent_color' => '#3498db',
                    'font_family' => 'Roboto',
                    'header_style' => 'classic'
                ],
                'payment_info' => [
                    'bank_name' => 'Crédit Agricole',
                    'account_number' => 'FR9876543210987654321098765',
                    'account_holder' => 'TechStore SAS',
                    'swift_code' => 'CRLYFRPP'
                ],
                'contact_email' => 'contact@techstore.fr',
                'contact_phone' => '+33 1 98 76 54 32',
                'about_text' => 'TechStore propose les dernières innovations technologiques au meilleur prix.',
                'social_links' => [
                    'facebook' => 'https://facebook.com/techstore',
                    'instagram' => 'https://instagram.com/techstore',
                    'youtube' => 'https://youtube.com/techstore'
                ],
                'owner_name' => 'Thomas Martin',
                'owner_email' => 'thomas.martin@techstore.fr',
                'owner_phone' => '+33 6 98 76 54 32',
                'owner_address' => '456 Rue de la Tech, 69001 Lyon, France',
                'owner_website' => 'https://thomas-martin-tech.com',
                'owner_bio' => 'Expert en technologie et entrepreneur passionné. Fondateur de TechStore, je m\'efforce de rendre la technologie accessible à tous.',
                'is_active' => true
            ],
            [
                'template_id' => 3, // Minimalist Fashion
                'name' => 'LuxeMode',
                'slug' => 'luxemode',
                'domain' => 'luxemode.paris',
                'description' => 'Boutique de mode de luxe',
                'logo' => '/shops/luxemode/logo.png',
                'banner_image' => '/shops/luxemode/banner.jpg',
                'theme_settings' => [
                    'primary_color' => '#ffffff',
                    'secondary_color' => '#f5f5f5',
                    'accent_color' => '#000000',
                    'font_family' => 'Playfair Display',
                    'header_style' => 'minimal'
                ],
                'payment_info' => [
                    'bank_name' => 'BNP Paribas',
                    'account_number' => 'FR4567891234567891234567891',
                    'account_holder' => 'LuxeMode Paris',
                    'swift_code' => 'BNPAFRPP'
                ],
                'contact_email' => 'contact@luxemode.paris',
                'contact_phone' => '+33 1 45 67 89 12',
                'about_text' => 'LuxeMode Paris vous propose une sélection raffinée de pièces de luxe.',
                'social_links' => [
                    'instagram' => 'https://instagram.com/luxemodeparis',
                    'pinterest' => 'https://pinterest.com/luxemodeparis'
                ],
                'owner_name' => 'Sophie Laurent',
                'owner_email' => 'sophie.laurent@luxemode.paris',
                'owner_phone' => '+33 6 45 67 89 12',
                'owner_address' => '789 Rue du Faubourg Saint-Honoré, 75008 Paris, France',
                'owner_website' => 'https://sophie-laurent-luxe.com',
                'owner_bio' => 'Styliste de luxe et créatrice de mode. LuxeMode Paris incarne l\'excellence française et l\'art de vivre à la parisienne.',
                'is_active' => true
            ]
        ];

        foreach ($shops as $shop) {
            Shop::create($shop);
        }
    }
}
