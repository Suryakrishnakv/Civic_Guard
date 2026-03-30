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
        Schema::table('report_photos', function (Blueprint $table) {
            $table->text('photo_content')->nullable();
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->text('photo_content')->nullable();
            $table->text('resolution_photo_content')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_photos', function (Blueprint $table) {
            $table->dropColumn('photo_content');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('photo_content');
            $table->dropColumn('resolution_photo_content');
        });
    }
};
