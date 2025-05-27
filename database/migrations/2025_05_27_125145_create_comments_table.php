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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Wie heeft gereageerd (kan null zijn voor gasten)
            $table->foreignId('news_id')->constrained()->onDelete('cascade'); // Bij welk nieuws_id hoort deze commentaar
            $table->text('content'); // De inhoud van de commentaar
            $table->timestamps(); // created_at en updated_at tijdstempels
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
