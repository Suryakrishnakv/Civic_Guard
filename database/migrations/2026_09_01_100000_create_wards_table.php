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
        Schema::create('wards', function (Blueprint $table) {
            $table->id();
            $table->integer('ward_number')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('boundaries'); // Array of [lat, lng] coordinates forming the polygon
            $table->decimal('center_lat', 10, 8)->nullable();
            $table->decimal('center_lng', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wards');
    }
};
