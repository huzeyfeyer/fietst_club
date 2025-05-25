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
        Schema::table('users', function (Blueprint $table) {
            $table->string('display_name')->nullable()->after('name'); // Optionele weergavenaam
            $table->date('birthday')->nullable()->after('email_verified_at'); // Geboortedatum
            $table->string('profile_photo_path')->nullable()->after('birthday'); // Pad naar profielfoto
            $table->text('about_me')->nullable()->after('profile_photo_path'); // Korte 'over mij' tekst
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['display_name', 'birthday', 'profile_photo_path', 'about_me']);
        });
    }
};
