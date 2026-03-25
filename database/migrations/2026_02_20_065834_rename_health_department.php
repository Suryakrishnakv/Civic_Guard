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
        \Illuminate\Support\Facades\DB::table('users')
            ->where('department', 'Health & Sanitation')
            ->update(['department' => 'Health & Sanitation Department']);

        \Illuminate\Support\Facades\DB::table('reports')
            ->where('department', 'Health & Sanitation')
            ->update(['department' => 'Health & Sanitation Department']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::table('users')
            ->where('department', 'Health & Sanitation Department')
            ->update(['department' => 'Health & Sanitation']);

        \Illuminate\Support\Facades\DB::table('reports')
            ->where('department', 'Health & Sanitation Department')
            ->update(['department' => 'Health & Sanitation']);
    }
};
