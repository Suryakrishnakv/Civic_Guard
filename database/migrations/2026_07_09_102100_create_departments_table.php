<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->json('categories')->nullable();
            $table->timestamps();
        });

        // Insert initial fallback / default departments and their respective works (categories)
        $defaultDepartments = [
            [
                'name' => 'Health & Sanitation Department',
                'categories' => json_encode([
                    'Mosquito Issue',
                    'Sanitation Problem',
                    'Stray Animal'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Engineering & Public Works Department',
                'categories' => json_encode([
                    'Road Damage',
                    'Drainage Block',
                    'Broken Footpath'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Solid Waste Management Department',
                'categories' => json_encode([
                    'Garbage Not Collected',
                    'Overflowing Bin',
                    'Illegal Dumping'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Electrical & Street Lighting Department',
                'categories' => json_encode([
                    'Street Light Not Working',
                    'Damaged Electric Pole',
                    'Open Street Wiring'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Water Supply & Sewerage Department',
                'categories' => json_encode([
                    'No Water Supply',
                    'Water Leakage',
                    'Sewer Overflow'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Town Planning & Building Department',
                'categories' => json_encode([
                    'Illegal Construction',
                    'Building Safety Issue',
                    'Road Encroachment'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('departments')->insert($defaultDepartments);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
