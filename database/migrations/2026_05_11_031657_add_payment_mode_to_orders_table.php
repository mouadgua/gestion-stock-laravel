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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('mode_paiement', ['paypal', 'cod'])->default('cod')->after('telephone_livraison');
            $table->string('paypal_paiement_id')->nullable()->unique()->after('mode_paiement');
            $table->string('paypal_payer_id')->nullable()->after('paypal_paiement_id');
            $table->enum('statut_paiement', ['en_attente', 'paye', 'echoue', 'rembourse'])->default('en_attente')->after('paypal_payer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['mode_paiement', 'paypal_paiement_id', 'paypal_payer_id', 'statut_paiement']);
        });
    }
};