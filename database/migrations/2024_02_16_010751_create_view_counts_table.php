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
        Schema::create('view_counts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('january')->nullable();
            $table->bigInteger('february')->nullable();
            $table->bigInteger('march')->nullable();
            $table->bigInteger('april')->nullable();
            $table->bigInteger('may')->nullable();
            $table->bigInteger('june')->nullable();
            $table->bigInteger('july')->nullable();
            $table->bigInteger('august')->nullable();
            $table->bigInteger('september')->nullable();
            $table->bigInteger('october')->nullable();
            $table->bigInteger('november')->nullable();
            $table->bigInteger('december')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('view_counts');
    }
};
