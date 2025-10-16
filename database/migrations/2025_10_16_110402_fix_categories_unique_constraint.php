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
        Schema::table('categories', function (Blueprint $table) {
            // Supprimer l'ancienne contrainte d'unicité sur slug
            $table->dropUnique(['slug']);
            
            // Ajouter une nouvelle contrainte d'unicité sur slug + shop_id
            $table->unique(['slug', 'shop_id'], 'categories_slug_shop_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Supprimer la contrainte composite
            $table->dropUnique('categories_slug_shop_unique');
            
            // Remettre l'ancienne contrainte d'unicité sur slug seulement
            $table->unique('slug');
        });
    }
};
