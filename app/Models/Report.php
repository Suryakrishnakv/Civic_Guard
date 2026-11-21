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
        'photo_content',
        'location',
        'status',
        'remarks',
        'resolution_photo_path',
        'resolution_photo_content',
        'latitude',
        'longitude',
        'address',
        'support_count',
        'assigned_officer_id',
        'ward_id',
        'priority_score',
        'sla_deadline',
        'rating',
        'feedback',
        'is_spam',
        'merged_into_id',
    ];

    public static function getCategoryDepartmentMap()
    {
        try {
            if (class_exists(\App\Models\Department::class) && \Schema::hasTable('departments')) {
                $map = [];
                $depts = \App\Models\Department::all();
                if ($depts->isNotEmpty()) {
                    foreach ($depts as $dept) {
                        $categories = $dept->categories;
                        if (is_array($categories)) {
                            foreach ($categories as $category) {
                                $map[$category] = $dept->name;
                            }
                        }
                    }
                    return $map;
                }
            }
        } catch (\Throwable $e) {
            // Fallback to static mapping during migration/testing
        }

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

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    protected static function booted()
    {
        static::created(function ($report) {
            $report->statusHistories()->create([
                'status' => $report->status ?? 'Pending',
                'remarks' => 'Complaint filed.',
                'changed_by' => auth()->id() ?? $report->user_id,
            ]);
            
            if ($report->assigned_officer_id) {
                $officer = User::find($report->assigned_officer_id);
                if ($officer) {
                    $report->statusHistories()->create([
                        'status' => $report->status ?? 'Pending',
                        'remarks' => "Automatically assigned to officer: {$officer->name} based on workload balance.",
                        'changed_by' => null,
                    ]);
                }
            }
        });

        static::updating(function ($report) {
            if ($report->isDirty('status') || $report->isDirty('remarks')) {
                $report->statusHistories()->create([
                    'status' => $report->status,
                    'remarks' => $report->remarks ?? 'Status updated.',
                    'changed_by' => auth()->id(),
                ]);
            }
            if ($report->isDirty('assigned_officer_id') && $report->assigned_officer_id) {
                $officer = User::find($report->assigned_officer_id);
                if ($officer) {
                    $report->statusHistories()->create([
                        'status' => $report->status,
                        'remarks' => "Assigned to officer: {$officer->name}.",
                        'changed_by' => auth()->id(),
                    ]);
                }
            }
        });
    }

    public function recalculatePriorityScore()
    {
        $categoryModel = Category::where('name', $this->category)->first();
        $severity = $categoryModel ? $categoryModel->severity : 'Medium';
        
        $severityPoints = [
            'Low' => 10,
            'Medium' => 30,
            'High' => 50
        ][$severity] ?? 30;

        $supportsCount = $this->support_count;
        $supportsPoints = $supportsCount * 5;

        $daysOld = $this->created_at ? $this->created_at->diffInDays(now()) : 0;
        $agePoints = $daysOld * 2;

        $this->priority_score = $severityPoints + $supportsPoints + $agePoints;
        $this->saveQuietly(); // Avoid infinite loops in updating hooks
    }

    public function assignedOfficer()
    {
        return $this->belongsTo(User::class, 'assigned_officer_id');
    }

    public function supports()
    {
        return $this->hasMany(Support::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(ReportStatusHistory::class);
    }

    public function mergedInto()
    {
        return $this->belongsTo(Report::class, 'merged_into_id');
    }

    public function mergedReports()
    {
        return $this->hasMany(Report::class, 'merged_into_id');
    }

    public function photos()
    {
        return $this->hasMany(ReportPhoto::class);
    }

    public function reportedPhotos()
    {
        return $this->hasMany(ReportPhoto::class)->where('type', 'reported');
    }

    public function resolutionPhotos()
    {
        return $this->hasMany(ReportPhoto::class)->where('type', 'resolution');
    }
}
