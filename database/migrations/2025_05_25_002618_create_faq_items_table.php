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
        Schema::create('faq_items', function (Blueprint $table) {
            $table->id();
            $table->text('question'); // De vraag
            $table->text('answer'); // Het antwoord
            $table->foreignId('faq_category_id')
                  ->nullable() // Maak het nullable als een FAQ item zonder categorie mogelijk moet zijn
                  ->constrained('faq_categories') // Verwijst naar de 'id' kolom in de 'faq_categories' tabel
                  ->onDelete('cascade'); // Als een categorie verwijderd wordt, verwijder ook de bijbehorende FAQ items
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq_items');
    }
};
