<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;

    protected $fillable = [
        'ward_number',
        'name',
        'description',
        'boundaries',
        'center_lat',
        'center_lng',
    ];

    protected $casts = [
        'boundaries' => 'array',
        'center_lat' => 'float',
        'center_lng' => 'float',
        'ward_number' => 'integer',
    ];

    /**
     * Reports filed in this ward.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Officers assigned to this municipal ward.
     */
    public function officers()
    {
        return $this->belongsToMany(User::class, 'ward_user')->where('role', User::ROLE_OFFICER);
    }

    /**
     * All users assigned to this ward.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'ward_user')->withTimestamps();
    }

    /**
     * Ray-Casting Algorithm to test if a GPS point (lat, lng) falls inside this ward's polygon.
     * Works identically across PostgreSQL, MySQL, and SQLite.
     *
     * @param float $lat
     * @param float $lng
     * @return bool
     */
    public function containsPoint(float $lat, float $lng): bool
    {
        $polygon = $this->boundaries;
        if (empty($polygon) || !is_array($polygon) || count($polygon) < 3) {
            return false;
        }

        // Bounding box pre-check
        $lats = array_column($polygon, 0);
        $lngs = array_column($polygon, 1);
        if ($lat < min($lats) || $lat > max($lats) || $lng < min($lngs) || $lng > max($lngs)) {
            return false;
        }

        $inside = false;
        $numPoints = count($polygon);

        for ($i = 0, $j = $numPoints - 1; $i < $numPoints; $j = $i++) {
            $xi = (float) $polygon[$i][0];
            $yi = (float) $polygon[$i][1];
            $xj = (float) $polygon[$j][0];
            $yj = (float) $polygon[$j][1];

            // Ray-casting crossing test
            $intersect = (($yi > $lng) !== ($yj > $lng)) &&
                ($lat < ($xj - $xi) * ($lng - $yi) / (($yj - $yi) ?: 0.0000000001) + $xi);

            if ($intersect) {
                $inside = !$inside;
            }
        }

        return $inside;
    }

    /**
     * Automatically find the ward containing the given GPS coordinates.
     *
     * @param float|null $lat
     * @param float|null $lng
     * @return Ward|null
     */
    public static function findWardByCoordinates(?float $lat, ?float $lng): ?self
    {
        if ($lat === null || $lng === null) {
            return null;
        }

        $wards = self::all();
        foreach ($wards as $ward) {
            if ($ward->containsPoint($lat, $lng)) {
                return $ward;
            }
        }

        return null;
    }

    /**
     * Automatically calculate centroid coordinates from boundaries polygon.
     */
    public static function calculateCentroid(array $boundaries): array
    {
        if (empty($boundaries)) {
            return ['lat' => null, 'lng' => null];
        }

        $sumLat = 0;
        $sumLng = 0;
        $count = count($boundaries);

        foreach ($boundaries as $point) {
            $sumLat += (float) $point[0];
            $sumLng += (float) $point[1];
        }

        return [
            'lat' => round($sumLat / $count, 7),
            'lng' => round($sumLng / $count, 7),
        ];
    }

    /**
     * Analytics and statistics helpers
     */
    public function getResolutionEfficiencyAttribute(): float
    {
        $total = $this->reports()->count();
        if ($total === 0) {
            return 0.0;
        }
        $repaired = $this->reports()->where('status', 'Repaired')->count();
        return round(($repaired / $total) * 100, 1);
    }

    public function getPendingCountAttribute(): int
    {
        return $this->reports()->where('status', 'Pending')->count();
    }

    public function getInProgressCountAttribute(): int
    {
        return $this->reports()->where('status', 'In Progress')->count();
    }

    public function getRepairedCountAttribute(): int
    {
        return $this->reports()->where('status', 'Repaired')->count();
    }

    public function getRejectedCountAttribute(): int
    {
        return $this->reports()->where('status', 'Rejected')->count();
    }
}
