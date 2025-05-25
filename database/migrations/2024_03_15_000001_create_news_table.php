<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Maak een nieuwe nieuwstabel aan
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');        // Nieuws titel
            $table->text('content');        // Nieuws inhoud
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Wie heeft het nieuws geplaatst
            $table->timestamps();           // Wanneer aangemaakt
        });
    }

    /**
     * Verwijder de nieuwstabel
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
}; 