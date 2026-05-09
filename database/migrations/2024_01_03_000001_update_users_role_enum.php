<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modify the role column to include 'livreur'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('acheteur', 'livreur', 'admin') DEFAULT 'acheteur'");
    }

    public function down(): void
    {
        // Revert back to original roles
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'client') DEFAULT 'client'");
    }
};