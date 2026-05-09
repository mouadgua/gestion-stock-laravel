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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('id_ligne');
            $table->foreignId('id_commande')->constrained('orders', 'id_commande')->onDelete('cascade');
            $table->foreignId('id_produit')->constrained('products', 'id_produit')->onDelete('cascade');
            $table->integer('quantite')->default(1);
            $table->float('sous_total', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};