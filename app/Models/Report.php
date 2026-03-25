<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'category',
        'department',
        'description',
        'photo_path',
        'location',
        'status',
        'priority',
        'remarks',
        'resolution_photo_path',
    ];

    public static function getCategoryDepartmentMap()
    {
        return [
            // Health & Sanitation
            'Mosquito Issue' => User::DEPT_HEALTH,
            'Sanitation Problem' => User::DEPT_HEALTH,
            'Stray Animal' => User::DEPT_HEALTH,

            // Engineering & Public Works
            'Road Damage' => User::DEPT_ENGINEERING,
            'Drainage Block' => User::DEPT_ENGINEERING,
            'Broken Footpath' => User::DEPT_ENGINEERING,

            // Solid Waste Management
            'Garbage Not Collected' => User::DEPT_WASTE,
            'Overflowing Bin' => User::DEPT_WASTE,
            'Illegal Dumping' => User::DEPT_WASTE,

            // Electrical & Street Lighting
            'Street Light Not Working' => User::DEPT_ELECTRICITY,
            'Damaged Electric Pole' => User::DEPT_ELECTRICITY,
            'Open Street Wiring' => User::DEPT_ELECTRICITY,

            // Water Supply & Sewerage
            'No Water Supply' => User::DEPT_WATER,
            'Water Leakage' => User::DEPT_WATER,
            'Sewer Overflow' => User::DEPT_WATER,

            // Town Planning & Building
            'Illegal Construction' => User::DEPT_PLANNING,
            'Building Safety Issue' => User::DEPT_PLANNING,
            'Road Encroachment' => User::DEPT_PLANNING,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
