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
        // Update user departments
        \DB::table('users')->where('department', 'Engineering')->update(['department' => \App\Models\User::DEPT_ENGINEERING]);
        \DB::table('users')->where('department', 'Waster')->update(['department' => \App\Models\User::DEPT_WASTE]); // Just in case of typo, but Waste is likely
        \DB::table('users')->where('department', 'Waste')->update(['department' => \App\Models\User::DEPT_WASTE]);
        \DB::table('users')->where('department', 'Health')->update(['department' => \App\Models\User::DEPT_HEALTH]);
        \DB::table('users')->where('department', 'Water')->update(['department' => \App\Models\User::DEPT_WATER]);
        \DB::table('users')->where('department', 'Electricity')->update(['department' => \App\Models\User::DEPT_ELECTRICITY]);
        \DB::table('users')->where('department', 'Town Planning')->update(['department' => \App\Models\User::DEPT_PLANNING]);

        // Update report departments too, just in case any old reports exist
        \DB::table('reports')->where('department', 'Engineering')->update(['department' => \App\Models\User::DEPT_ENGINEERING]);
        \DB::table('reports')->where('department', 'Waste')->update(['department' => \App\Models\User::DEPT_WASTE]);
        \DB::table('reports')->where('department', 'Health')->update(['department' => \App\Models\User::DEPT_HEALTH]);
        \DB::table('reports')->where('department', 'Water')->update(['department' => \App\Models\User::DEPT_WATER]);
        \DB::table('reports')->where('department', 'Electricity')->update(['department' => \App\Models\User::DEPT_ELECTRICITY]);
        \DB::table('reports')->where('department', 'Town Planning')->update(['department' => \App\Models\User::DEPT_PLANNING]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to reverse this without losing data fidelity or tracking previous state.
        // Leaving empty as this is a data fix.
    }
};
