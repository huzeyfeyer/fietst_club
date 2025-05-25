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
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Naam van de afzender
            $table->string('email'); // E-mailadres van de afzender
            $table->string('subject')->nullable(); // Onderwerp van het bericht
            $table->text('message'); // De inhoud van het bericht
            $table->boolean('is_read')->default(false); // Markering gelezen/ongelezen
            $table->timestamp('archived_at')->nullable(); // Voor archivering
            $table->timestamps(); // created_at en updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
