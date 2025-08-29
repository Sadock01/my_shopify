<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shops = Shop::all();
        $categories = Category::all();

        foreach ($shops as $shop) {
            // Produits pour Horizon Fashion
            if ($shop->slug === 'horizon-fashion') {
                $this->createHorizonProducts($shop, $categories);
            }
            
            // Produits pour TechStore
            if ($shop->slug === 'techstore') {
                $this->createTechProducts($shop, $categories);
            }
            
            // Produits pour LuxeMode
            if ($shop->slug === 'luxemode') {
                $this->createLuxeProducts($shop, $categories);
            }
        }
    }

    private function createHorizonProducts($shop, $categories)
    {
        $clothingCategory = $categories->where('slug', 'vetements')->first();
        $accessoriesCategory = $categories->where('slug', 'accessoires')->first();

        $products = [
            [
                'category_id' => $clothingCategory->id,
                'name' => 'Denim Jacket',
                'description' => 'Veste en jean classique avec un style moderne et élégant. Parfaite pour toutes les occasions.',
                'price' => 230.00,
                'original_price' => 279.00,
                'image' => 'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?w=500&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?w=500&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=500&h=600&fit=crop'
                ],
                'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
                'colors' => ['Bleu', 'Noir'],
                'stock' => 25,
                'is_featured' => true
            ],
            [
                'category_id' => $clothingCategory->id,
                'name' => 'Leah Cashmere Zip Sweater',
                'description' => 'Pull en cachemire zippé avec un design raffiné et un confort exceptionnel.',
                'price' => 210.00,
                'original_price' => 250.00,
                'image' => 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=500&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=500&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&h=600&fit=crop'
                ],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Gris', 'Beige', 'Noir'],
                'stock' => 18,
                'is_featured' => true
            ],
            [
                'category_id' => $clothingCategory->id,
                'name' => 'Jahmad Long Sleeve Polo',
                'description' => 'Polo à manches longues en coton premium avec un style sportif et élégant.',
                'price' => 210.00,
                'original_price' => 240.00,
                'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=500&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=500&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=500&h=600&fit=crop'
                ],
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                'colors' => ['Vert', 'Bleu', 'Blanc'],
                'stock' => 30,
                'is_featured' => false
            ],
            [
                'category_id' => $clothingCategory->id,
                'name' => 'Mole Yarn Stripe Skipper Knit',
                'description' => 'Pull en maille rayé avec un style rétro et moderne. Parfait pour l\'automne.',
                'price' => 203.00,
                'original_price' => 228.00,
                'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=500&h=600&fit=crop'
                ],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Orange/Rouge', 'Bleu/Blanc'],
                'stock' => 15,
                'is_featured' => true
            ],
            [
                'category_id' => $clothingCategory->id,
                'name' => '1Tuck Tapered Pants',
                'description' => 'Pantalon fuselé avec un pli unique à la taille. Style décontracté et élégant.',
                'price' => 203.00,
                'original_price' => 240.00,
                'image' => 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=500&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=500&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=500&h=600&fit=crop'
                ],
                'sizes' => ['30', '32', '34', '36', '38'],
                'colors' => ['Noir', 'Beige', 'Olive'],
                'stock' => 22,
                'is_featured' => false
            ],
            [
                'category_id' => $clothingCategory->id,
                'name' => 'Alex Merino Wool Open Placket Polo',
                'description' => 'Polo en laine mérinos avec col ouvert. Matériau premium et confort exceptionnel.',
                'price' => 203.00,
                'original_price' => 230.00,
                'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=500&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=500&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=500&h=600&fit=crop'
                ],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Crème', 'Gris', 'Bleu marine'],
                'stock' => 12,
                'is_featured' => false
            ],
            [
                'category_id' => $clothingCategory->id,
                'name' => 'Allied Down Hooded Blouson',
                'description' => 'Blouson matelassé avec capuche. Parfait pour les journées fraîches.',
                'price' => 523.00,
                'original_price' => 580.00,
                'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=500&h=600&fit=crop'
                ],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Noir', 'Marron', 'Bleu'],
                'stock' => 8,
                'is_featured' => true
            ]
        ];

        foreach ($products as $productData) {
            Product::create(array_merge($productData, ['shop_id' => $shop->id]));
        }
    }

    private function createTechProducts($shop, $categories)
    {
        $electronicsCategory = $categories->where('slug', 'electronique')->first();

        $products = [
            [
                'category_id' => $electronicsCategory->id,
                'name' => 'iPhone 15 Pro',
                'description' => 'Le dernier iPhone avec puce A17 Pro et appareil photo professionnel.',
                'price' => 1199.00,
                'original_price' => 1299.00,
                'image' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=500&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=500&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=600&fit=crop'
                ],
                'sizes' => ['128GB', '256GB', '512GB'],
                'colors' => ['Titanium', 'Titanium Blue', 'Titanium White'],
                'stock' => 15,
                'is_featured' => true
            ],
            [
                'category_id' => $electronicsCategory->id,
                'name' => 'MacBook Air M2',
                'description' => 'Ordinateur portable ultra-léger avec puce M2 et autonomie exceptionnelle.',
                'price' => 1499.00,
                'original_price' => 1599.00,
                'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=500&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=500&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=500&h=600&fit=crop'
                ],
                'sizes' => ['8GB', '16GB'],
                'colors' => ['Argent', 'Or', 'Gris sidéral'],
                'stock' => 8,
                'is_featured' => true
            ]
        ];

        foreach ($products as $productData) {
            Product::create(array_merge($productData, ['shop_id' => $shop->id]));
        }
    }

    private function createLuxeProducts($shop, $categories)
    {
        $clothingCategory = $categories->where('slug', 'vetements')->first();
        $accessoriesCategory = $categories->where('slug', 'accessoires')->first();

        $products = [
            [
                'category_id' => $clothingCategory->id,
                'name' => 'Costume en laine italienne',
                'description' => 'Costume trois pièces en laine italienne de première qualité. Coupe sur mesure.',
                'price' => 899.00,
                'original_price' => 1200.00,
                'image' => 'https://images.unsplash.com/photo-1593030761757-71fae45fa0e7?w=500&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1593030761757-71fae45fa0e7?w=500&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=500&h=600&fit=crop'
                ],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Navy', 'Charcoal', 'Anthracite'],
                'stock' => 5,
                'is_featured' => true
            ],
            [
                'category_id' => $accessoriesCategory->id,
                'name' => 'Montre de luxe automatique',
                'description' => 'Montre automatique suisse avec mouvement mécanique. Design intemporel.',
                'price' => 2500.00,
                'original_price' => 3000.00,
                'image' => 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=500&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=500&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1547996160-81dfa63595aa?w=500&h=600&fit=crop'
                ],
                'sizes' => ['40mm', '42mm'],
                'colors' => ['Acier', 'Or rose', 'Or blanc'],
                'stock' => 3,
                'is_featured' => true
            ]
        ];

        foreach ($products as $productData) {
            Product::create(array_merge($productData, ['shop_id' => $shop->id]));
        }
    }
}
