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
        $mappings = [
            'Engineering & Public Works' => 'Engineering & Public Works Department',
            'Solid Waste Management' => 'Solid Waste Management Department',
            'Water Supply & Sewerage' => 'Water Supply & Sewerage Department',
            'Electrical & Street Lighting' => 'Electrical & Street Lighting Department',
            'Town Planning & Building' => 'Town Planning & Building Department'
        ];

        foreach ($mappings as $old => $new) {
            DB::table('users')->where('department', $old)->update(['department' => $new]);
            DB::table('reports')->where('department', $old)->update(['department' => $new]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $mappings = [
            'Engineering & Public Works Department' => 'Engineering & Public Works',
            'Solid Waste Management Department' => 'Solid Waste Management',
            'Water Supply & Sewerage Department' => 'Water Supply & Sewerage',
            'Electrical & Street Lighting Department' => 'Electrical & Street Lighting',
            'Town Planning & Building Department' => 'Town Planning & Building'
        ];

        foreach ($mappings as $new => $old) {
            DB::table('users')->where('department', $new)->update(['department' => $old]);
            DB::table('reports')->where('department', $new)->update(['department' => $old]);
        }
    }
};
