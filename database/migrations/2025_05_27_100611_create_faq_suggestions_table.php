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
        Schema::create('faq_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Wie heeft het ingediend
            $table->text('question'); // De voorgestelde vraag
            $table->string('status')->default('pending'); // Status: pending, approved, rejected, etc.
            $table->text('admin_notes')->nullable(); // Optionele notities van de admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq_suggestions');
    }
};
