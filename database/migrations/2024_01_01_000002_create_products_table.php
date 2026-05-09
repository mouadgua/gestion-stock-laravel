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
        Schema::create('products', function (Blueprint $table) {
            $table->id('id_produit');
            $table->string('nom_produit');
            $table->text('description')->nullable();
            $table->float('prix', 8, 2);
            $table->integer('stock')->default(0);
            $table->string('image')->nullable(); // Path to uploaded image
            $table->string('slug')->unique();
            $table->boolean('est_actif')->default(true);
            $table->foreignId('categorie_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};