<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# MyShopify - API Documentation

## 🏪 Vue d'ensemble

MyShopify est une plateforme de gestion multi-boutiques permettant à un admin de créer et gérer plusieurs boutiques en ligne avec différents templates.

### Fonctionnalités principales :
- ✅ **Gestion des templates** de boutiques
- ✅ **Création de boutiques** avec personnalisation
- ✅ **Gestion des produits** et catégories
- ✅ **Système de commandes** (notification uniquement)
- ✅ **Filtrage par catégories**
- ✅ **Thèmes personnalisables**

## 🚀 Installation

```bash
# Cloner le projet
git clone [repository-url]
cd myshopify

# Installer les dépendances
composer install

# Configuration de l'environnement
cp .env.example .env
php artisan key:generate

# Configuration de la base de données
# Modifier .env avec vos paramètres DB

# Migrations et seeders
php artisan migrate
php artisan db:seed

# Démarrer le serveur
php artisan serve
```

## 📋 Structure de la base de données

### Tables principales :
- **shop_templates** : Templates de boutiques disponibles
- **shops** : Boutiques créées par l'admin
- **categories** : Catégories de produits
- **products** : Produits des boutiques
- **orders** : Commandes des clients
- **users** : Utilisateurs (admin et clients)

## 🔌 API Endpoints

### Base URL
```
http://localhost:8000/api
```

### 1. Templates de boutiques

#### Liste des templates
```http
GET /api/templates
```

#### Détails d'un template
```http
GET /api/templates/{id}
```

#### Créer un template
```http
POST /api/templates
Content-Type: application/json

{
    "name": "Mon Template",
    "slug": "mon-template",
    "description": "Description du template",
    "preview_image": "/path/to/image.jpg",
    "theme_colors": {
        "primary": "#000000",
        "secondary": "#ffffff"
    },
    "layout_options": {
        "header_style": "minimal",
        "product_grid": "4-columns"
    }
}
```

### 2. Boutiques

#### Liste des boutiques
```http
GET /api/shops
```

#### Créer une boutique
```http
POST /api/shops
Content-Type: application/json

{
    "template_id": 1,
    "name": "Ma Boutique",
    "slug": "ma-boutique",
    "description": "Description de la boutique",
    "theme_settings": {
        "primary_color": "#000000",
        "font_family": "Arial"
    },
    "payment_info": {
        "bank_account": "FR123456789",
        "bank_name": "Ma Banque"
    }
}
```

### 3. Produits

#### Liste des produits
```http
GET /api/products
```

#### Produits d'une boutique
```http
GET /api/shops/{shop_id}/products
```

#### Créer un produit
```http
POST /api/products
Content-Type: application/json

{
    "shop_id": 1,
    "category_id": 1,
    "name": "T-shirt Premium",
    "description": "T-shirt en coton bio",
    "price": 29.99,
    "original_price": 39.99,
    "image": "/products/tshirt.jpg",
    "sizes": ["S", "M", "L", "XL"],
    "colors": ["Noir", "Blanc", "Bleu"],
    "stock": 100
}
```

### 4. Catégories

#### Liste des catégories
```http
GET /api/categories
```

#### Produits par catégorie
```http
GET /api/shops/{shop_id}/products/category/{category_id}
```

### 5. Commandes

#### Passer une commande
```http
POST /api/shops/{shop_id}/orders
Content-Type: application/json

{
    "customer_name": "Jean Dupont",
    "customer_email": "jean@example.com",
    "customer_phone": "0123456789",
    "customer_address": "123 Rue de la Paix, Paris",
    "items": [
        {
            "product_id": 1,
            "quantity": 2,
            "size": "M",
            "color": "Noir"
        }
    ],
    "total_amount": 59.98,
    "notes": "Livraison en point relais"
}
```

#### Liste des commandes d'une boutique
```http
GET /api/shops/{shop_id}/orders
```

## 🎨 Templates disponibles

### 1. Horizon Modern
- **Style** : Moderne et épuré
- **Couleurs** : Noir et blanc
- **Layout** : 4 colonnes, header minimal
- **Idéal pour** : Mode contemporaine

### 2. Classic E-commerce
- **Style** : Classique et fonctionnel
- **Couleurs** : Bleu et gris
- **Layout** : 3 colonnes, header classique
- **Idéal pour** : E-commerce général

### 3. Minimalist Fashion
- **Style** : Minimaliste
- **Couleurs** : Blanc et noir
- **Layout** : 2 colonnes, design épuré
- **Idéal pour** : Mode haut de gamme

## 🔧 Configuration

### Variables d'environnement importantes :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=myshopify
DB_USERNAME=root
DB_PASSWORD=

APP_URL=http://localhost:8000
```

## 📱 Flux utilisateur

### Pour l'Admin :
1. Créer des templates de boutiques
2. Créer des boutiques avec un template
3. Ajouter des catégories et produits
4. Configurer les informations de paiement
5. Recevoir les notifications de commandes

### Pour les Clients :
1. Visiter une boutique
2. Parcourir les produits par catégorie
3. Ajouter au panier
4. Passer commande (notification à l'admin)
5. Paiement en externe (virement bancaire)

## 🚀 Prochaines étapes

- [ ] Interface admin (dashboard)
- [ ] Interface boutique (frontend)
- [ ] Système d'authentification
- [ ] Upload d'images
- [ ] Notifications en temps réel
- [ ] Système de recherche
- [ ] Gestion des stocks

## 📞 Support

Pour toute question ou problème, contactez l'équipe de développement.
