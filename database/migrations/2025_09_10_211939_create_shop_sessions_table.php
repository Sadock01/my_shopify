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
        Schema::create('shop_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->string('session_token')->unique();
            $table->json('cart_data')->nullable(); // Panier spécifique à la boutique
            $table->json('user_preferences')->nullable(); // Préférences utilisateur pour cette boutique
            $table->timestamp('last_activity')->useCurrent();
            $table->timestamps();
            
            $table->unique(['user_id', 'shop_id']); // Un utilisateur ne peut avoir qu'une session par boutique
            $table->index(['user_id', 'last_activity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_sessions');
    }
};