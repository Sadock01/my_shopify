<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('shop_templates')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('domain')->unique()->nullable(); // URL personnalisée de la boutique
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner_image')->nullable();
            $table->json('theme_settings'); // Paramètres du thème (couleurs, polices, etc.)
            $table->json('payment_info'); // Informations de paiement (compte bancaire, etc.)
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('about_text')->nullable();
            $table->json('social_links')->nullable(); // Liens réseaux sociaux
            
            // Informations du propriétaire
            $table->string('owner_name')->nullable(); // Nom du propriétaire
            $table->string('owner_email')->nullable(); // Email du propriétaire
            $table->string('owner_phone')->nullable(); // Téléphone du propriétaire
            $table->text('owner_address')->nullable(); // Adresse du propriétaire
            $table->string('owner_website')->nullable(); // Site web du propriétaire
            $table->text('owner_bio')->nullable(); // Biographie du propriétaire
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
