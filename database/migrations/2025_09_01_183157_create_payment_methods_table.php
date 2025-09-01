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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nom du moyen de paiement
            $table->string('type'); // Type: 'bank_transfer', 'check', 'cash', 'mobile_money', etc.
            $table->text('description')->nullable(); // Description détaillée
            $table->string('icon')->nullable(); // Icône ou image
            $table->json('details')->nullable(); // Détails spécifiques (IBAN, numéro de compte, etc.)
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
