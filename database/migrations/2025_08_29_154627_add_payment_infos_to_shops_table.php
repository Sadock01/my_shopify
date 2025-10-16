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
        Schema::table('shops', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->after('description');
            $table->string('account_holder')->nullable()->after('bank_name');
            $table->string('iban')->nullable()->after('account_holder');
            $table->string('bic')->nullable()->after('iban');
            $table->text('payment_instructions')->nullable()->after('bic');
            $table->json('payment_methods')->nullable()->after('payment_instructions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn([
                'bank_name',
                'account_holder', 
                'iban',
                'bic',
                'payment_instructions',
                'payment_methods'
            ]);
        });
    }
};

