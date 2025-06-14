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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('prix', 8, 2);
            $table->integer('quantite')->default(0);
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->foreignId('fournisseur_id')->nullable()->constrained('fournisseurs');
            $table->foreignId('emplacement_id')->nullable()->constrained('emplacements');
            $table->foreignId('created_by')->nullable()->constrained('users'); // si 'users' est la table des utilisateurs
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
