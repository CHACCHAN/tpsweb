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
        Schema::create('discord_medias', function (Blueprint $table) {
            $table->id();
            $table->boolean('auto_mode')->nullable();
            $table->boolean('notifical_link')->nullable();
            $table->boolean('paste_image')->nullable();
            $table->longText('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discord_medias');
    }
};
