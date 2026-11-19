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
        Schema::table('reports', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('address')->nullable();
            
            $table->integer('support_count')->default(0);
            $table->foreignId('assigned_officer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('priority_score')->default(0);
            $table->timestamp('sla_deadline')->nullable();
            
            $table->tinyInteger('rating')->nullable();
            $table->text('feedback')->nullable();
            
            $table->boolean('is_spam')->default(false);
            $table->foreignId('merged_into_id')->nullable()->constrained('reports')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['assigned_officer_id']);
            $table->dropForeign(['merged_into_id']);
            
            $table->dropColumn([
                'latitude',
                'longitude',
                'address',
                'support_count',
                'assigned_officer_id',
                'priority_score',
                'sla_deadline',
                'rating',
                'feedback',
                'is_spam',
                'merged_into_id'
            ]);
        });
    }
};
