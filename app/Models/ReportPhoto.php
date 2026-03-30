<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'photo_path',
        'photo_content',
        'type',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Get the display URL for the photo.
     * Prioritizes database storage (photo_content) over the filesystem path.
     */
    public function getUrlAttribute()
    {
        if ($this->photo_content) {
            return $this->photo_content;
        }
        
        return $this->photo_path ? asset('storage/' . $this->photo_path) : null;
    }

    /**
     * Check if the photo is a video.
     */
    public function getIsVideoAttribute()
    {
        if ($this->photo_content) {
            return str_contains($this->photo_content, 'video/');
        }

        if ($this->photo_path) {
            $extension = pathinfo($this->photo_path, PATHINFO_EXTENSION);
            return in_array(strtolower($extension), ['mp4', 'mov', 'avi', 'wmv']);
        }

        return false;
    }
}
