<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Storage;
use App\Models\Shop;
use App\Models\Product;

echo "Migration des images vers public/documents/\n";
echo "==========================================\n\n";

// Créer le dossier documents s'il n'existe pas
$documentsPath = public_path('documents');
if (!file_exists($documentsPath)) {
    mkdir($documentsPath, 0755, true);
    echo "✅ Dossier documents créé\n";
}

// Migrer les images des boutiques
echo "Migration des images des boutiques...\n";
$shops = Shop::all();
foreach ($shops as $shop) {
    echo "Boutique: {$shop->name}\n";
    
    // Logo
    if ($shop->logo && Storage::disk('public')->exists($shop->logo)) {
        $oldPath = storage_path('app/public/' . $shop->logo);
        $newPath = public_path('documents/' . $shop->logo);
        
        // Créer le dossier de destination
        $newDir = dirname($newPath);
        if (!file_exists($newDir)) {
            mkdir($newDir, 0755, true);
        }
        
        if (copy($oldPath, $newPath)) {
            echo "  ✅ Logo migré: {$shop->logo}\n";
        } else {
            echo "  ❌ Erreur migration logo: {$shop->logo}\n";
        }
    }
    
    // Favicon
    if ($shop->favicon && Storage::disk('public')->exists($shop->favicon)) {
        $oldPath = storage_path('app/public/' . $shop->favicon);
        $newPath = public_path('documents/' . $shop->favicon);
        
        $newDir = dirname($newPath);
        if (!file_exists($newDir)) {
            mkdir($newDir, 0755, true);
        }
        
        if (copy($oldPath, $newPath)) {
            echo "  ✅ Favicon migré: {$shop->favicon}\n";
        } else {
            echo "  ❌ Erreur migration favicon: {$shop->favicon}\n";
        }
    }
    
    // Bannière
    if ($shop->banner_image && Storage::disk('public')->exists($shop->banner_image)) {
        $oldPath = storage_path('app/public/' . $shop->banner_image);
        $newPath = public_path('documents/' . $shop->banner_image);
        
        $newDir = dirname($newPath);
        if (!file_exists($newDir)) {
            mkdir($newDir, 0755, true);
        }
        
        if (copy($oldPath, $newPath)) {
            echo "  ✅ Bannière migrée: {$shop->banner_image}\n";
        } else {
            echo "  ❌ Erreur migration bannière: {$shop->banner_image}\n";
        }
    }
}

// Migrer les images des produits
echo "\nMigration des images des produits...\n";
$products = Product::all();
foreach ($products as $product) {
    echo "Produit: {$product->name}\n";
    
    // Image principale
    if ($product->image && Storage::disk('public')->exists($product->image)) {
        $oldPath = storage_path('app/public/' . $product->image);
        $newPath = public_path('documents/' . $product->image);
        
        $newDir = dirname($newPath);
        if (!file_exists($newDir)) {
            mkdir($newDir, 0755, true);
        }
        
        if (copy($oldPath, $newPath)) {
            echo "  ✅ Image principale migrée: {$product->image}\n";
        } else {
            echo "  ❌ Erreur migration image: {$product->image}\n";
        }
    }
    
    // Images supplémentaires
    if ($product->images && is_array($product->images)) {
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image)) {
                $oldPath = storage_path('app/public/' . $image);
                $newPath = public_path('documents/' . $image);
                
                $newDir = dirname($newPath);
                if (!file_exists($newDir)) {
                    mkdir($newDir, 0755, true);
                }
                
                if (copy($oldPath, $newPath)) {
                    echo "  ✅ Image supplémentaire migrée: {$image}\n";
                } else {
                    echo "  ❌ Erreur migration image: {$image}\n";
                }
            }
        }
    }
}

echo "\n✅ Migration terminée!\n";
echo "N'oubliez pas de:\n";
echo "1. Vider le cache: php artisan cache:clear\n";
echo "2. Tester l'affichage des images\n";
echo "3. Supprimer l'ancien dossier storage/app/public si tout fonctionne\n";
