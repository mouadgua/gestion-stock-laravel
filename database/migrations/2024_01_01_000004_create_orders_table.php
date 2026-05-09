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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id_commande');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date_commande');
            $table->float('total', 10, 2)->default(0);
            $table->enum('statut', ['en_attente', 'expediee', 'livree', 'annulee'])->default('en_attente');
            $table->text('adresse_livraison')->nullable();
            $table->string('telephone_livraison')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};