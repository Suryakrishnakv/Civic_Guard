<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Models\Support;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function create()
    {
        $categories = array_keys(Report::getCategoryDepartmentMap());
        return view('reports.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'address' => 'nullable|string|max:1000',
            'photos' => 'required|array|min:1',
            'photos.*' => 'image|max:10240',
        ]);

        $map = Report::getCategoryDepartmentMap();
        $department = $map[$request->category] ?? null;

        $report = Report::create([
            'user_id' => Auth::id(),
            'type' => 'Damage',
            'category' => $request->category,
            'department' => $department,
            'description' => $request->description,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address ?? $request->location,
            'status' => 'Pending',
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                // Store standard file
                $path = $photo->store('reports', 'public');
                
                // Read and encode for persistence across ephemeral environments
                $base64 = base64_encode(file_get_contents($photo->getRealPath()));
                $mime = $photo->getClientMimeType();
                $fullBase64 = "data:$mime;base64,$base64";

                $report->photos()->create([
                    'photo_path' => $path,
                    'photo_content' => $fullBase64,
                    'type' => 'reported'
                ]);
                
                // Store first photo in main table for backward compatibility
                if ($index === 0) {
                    $report->update([
                        'photo_path' => $path,
                        'photo_content' => $fullBase64,
                    ]);
                }
            }
        }

        return redirect()->route('dashboard')->with('status', 'Report submitted successfully!');
    }

    /**
     * Detect nearby complaints within given radius (default 200m) to prevent duplicates.
     */
    public function checkDuplicate(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'category' => 'nullable|string',
            'radius' => 'nullable|numeric|min:10|max:5000',
        ]);

        $lat = (float) $request->latitude;
        $lng = (float) $request->longitude;
        $category = $request->category;
        $radiusMeters = (float) ($request->radius ?? 200);

        // Bounding box pre-filter for performance
        $latDelta = ($radiusMeters / 111320) * 1.5;
        $cosLat = max(cos(deg2rad($lat)), 0.01);
        $lngDelta = ($radiusMeters / (111320 * $cosLat)) * 1.5;

        $query = Report::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereIn('status', ['Pending', 'In Progress']);

        $query->whereBetween('latitude', [$lat - $latDelta, $lat + $latDelta])
              ->whereBetween('longitude', [$lng - $lngDelta, $lng + $lngDelta]);

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        $candidates = $query->with('photos')->get();

        $earthRadius = 6371000; // meters
        $duplicates = $candidates->map(function ($r) use ($lat, $lng, $earthRadius) {
            $dLat = deg2rad((float)$r->latitude - $lat);
            $dLon = deg2rad((float)$r->longitude - $lng);
            $a = sin($dLat / 2) * sin($dLat / 2) +
                 cos(deg2rad($lat)) * cos(deg2rad((float)$r->latitude)) *
                 sin($dLon / 2) * sin($dLon / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $distance = $earthRadius * $c;

            $photos = $r->photos->map(function ($p) {
                return $p->photo_content ?? ($p->photo_path ? asset('storage/' . $p->photo_path) : null);
            })->filter()->values()->toArray();

            if (empty($photos)) {
                $primary = $r->photo_content ?? ($r->photo_path ? asset('storage/' . $r->photo_path) : null);
                if ($primary) {
                    $photos[] = $primary;
                }
            }

            return [
                'id' => $r->id,
                'case_number' => '#' . str_pad($r->id, 5, '0', STR_PAD_LEFT),
                'category' => $r->category,
                'department' => $r->department,
                'status' => $r->status,
                'description' => Str::limit($r->description, 100),
                'location' => $r->location,
                'distance_meters' => round($distance, 1),
                'created_at' => $r->created_at ? $r->created_at->diffForHumans() : '',
                'photos' => $photos,
            ];
        })->filter(function ($item) use ($radiusMeters) {
            return $item['distance_meters'] <= $radiusMeters;
        })->sortBy('distance_meters')->values();

        return response()->json([
            'has_duplicates' => $duplicates->isNotEmpty(),
            'count' => $duplicates->count(),
            'radius' => $radiusMeters,
            'radius_meters' => $radiusMeters,
            'reports' => $duplicates,
        ]);
    }

    /**
     * Retrieve complaints as GeoJSON/JSON for the Leaflet GIS map.
     * Enforces strict role-based scoping:
     * - Citizens: authorized to access only their own complaints
     * - Officers: authorized to access only their department's complaints
     * - Admins: global access across all complaints
     */
    public function mapData(Request $request)
    {
        $user = Auth::user();
        $query = Report::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereBetween('latitude', [-90, 90])
            ->whereBetween('longitude', [-180, 180])
            ->where(function ($q) {
                $q->where('latitude', '!=', 0)
                  ->orWhere('longitude', '!=', 0);
            });

        // Role-based scope (Requirement 6):
        if ($user->isOfficer()) {
            $query->where('department', $user->department);
        } elseif ($user->isAdmin()) {
            if ($request->filled('department') && $request->department !== 'all') {
                $query->where('department', $request->department);
            }
        } else {
            // Citizen view: strictly authorized to access only their own complaints
            $query->where('user_id', $user->id);
        }

        // Category filter (Requirement 4)
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Status filter (Requirement 4)
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Submission Date filter (Requirement 4)
        if ($request->filled('date') && $request->date !== 'all') {
            switch ($request->date) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->where('created_at', '>=', now()->subDays(7));
                    break;
                case 'month':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
                case 'year':
                    $query->where('created_at', '>=', now()->subDays(365));
                    break;
            }
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Viewport bounds (Viewport-based optimization)
        if ($request->filled('bounds')) {
            $parts = explode(',', $request->bounds);
            if (count($parts) === 4) {
                $swLat = (float)$parts[0];
                $swLng = (float)$parts[1];
                $neLat = (float)$parts[2];
                $neLng = (float)$parts[3];
                $query->whereBetween('latitude', [min($swLat, $neLat), max($swLat, $neLat)])
                      ->whereBetween('longitude', [min($swLng, $neLng), max($swLng, $neLng)]);
            }
        }

        $reports = $query->with('photos')
            ->latest()
            ->take(1000)
            ->get();

        $stats = [
            'total' => $reports->count(),
            'pending' => $reports->where('status', 'Pending')->count(),
            'in_progress' => $reports->where('status', 'In Progress')->count(),
            'repaired' => $reports->where('status', 'Repaired')->count(),
            'rejected' => $reports->where('status', 'Rejected')->count(),
        ];

        $reportsList = $reports->map(function ($r) use ($user) {
            $photos = $r->photos->map(function ($p) {
                return $p->photo_content ?? ($p->photo_path ? asset('storage/' . $p->photo_path) : null);
            })->filter()->values()->toArray();

            if (empty($photos)) {
                $primary = $r->photo_content ?? ($r->photo_path ? asset('storage/' . $r->photo_path) : null);
                if ($primary) {
                    $photos[] = $primary;
                }
            }

            $colorMap = [
                'Pending' => '#ef4444',       // Red
                'In Progress' => '#3b82f6',   // Blue
                'Repaired' => '#10b981',      // Green
                'Rejected' => '#6b7280',      // Grey
            ];
            $markerColor = $colorMap[$r->status] ?? '#ef4444';

            return [
                'id' => $r->id,
                'case_number' => '#' . str_pad($r->id, 5, '0', STR_PAD_LEFT),
                'category' => $r->category,
                'department' => $r->department ?? 'General',
                'description' => Str::limit($r->description, 160),
                'status' => $r->status,
                'color' => $markerColor,
                'marker_color' => $markerColor,
                'location' => $r->location,
                'address' => $r->address ?? $r->location,
                'latitude' => (float)$r->latitude,
                'longitude' => (float)$r->longitude,
                'support_count' => (int)($r->support_count ?? 0),
                'photos' => $photos,
                'created_at' => $r->created_at ? $r->created_at->format('M d, Y h:i A') : '',
                'created_at_human' => $r->created_at ? $r->created_at->diffForHumans() : '',
                'view_url' => route('reports.show', $r->id),
                'detail_url' => route('reports.show', $r->id),
                'is_own' => (int)$r->user_id === (int)$user->id,
            ];
        });

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'total' => $reportsList->count(),
            'reports' => $reportsList,
            'features' => $reportsList->map(function ($item) {
                return [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [$item['longitude'], $item['latitude']],
                    ],
                    'properties' => $item,
                ];
            }),
        ]);
    }

    public function officerMap(Request $request)
    {
        $user = Auth::user();
        $departments = User::getDepartments();
        $categories = array_keys(Report::getCategoryDepartmentMap());
        return view('reports.map', compact('departments', 'categories'));
    }

    public function myReports()
    {
        $reports = Auth::user()->reports()
            ->whereIn('status', ['Pending', 'In Progress'])
            ->latest()
            ->get();
        return view('user.dashboard', compact('reports'));
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $announcements = $user->notifications()
            ->get()
            ->filter(function ($notification) use ($user) {
                return ($notification->data['user_id'] ?? null) !== $user->id;
            })
            ->take(5);

        $departments = User::getDepartments();
        $categories = array_keys(Report::getCategoryDepartmentMap());

        if ($user->isAdmin()) {
            $reports = Report::with('user')->latest()->get();
            $analytics = app(\App\Services\AnalyticsService::class)->getDashboardAnalytics($request);
            $stats = [
                'total_users' => User::where('role', User::ROLE_CITIZEN)->count(),
                'total_officers' => User::where('role', User::ROLE_OFFICER)->count(),
                'total' => $analytics['kpis']['total'],
                'pending' => $analytics['kpis']['pending'],
                'in_progress' => $analytics['kpis']['in_progress'],
                'resolved' => $analytics['kpis']['repaired'],
                'rejected' => $analytics['kpis']['rejected'],
                'efficiency' => $analytics['kpis']['efficiency'],
                'avg_resolution_time' => $analytics['resolution_time_stats']['formatted'],
            ];
            $sentBroadcasts = \App\Models\Announcement::where('user_id', $user->id)->latest()->get();
            return view('admin.dashboard', compact('reports', 'stats', 'announcements', 'sentBroadcasts', 'departments', 'categories', 'analytics'));
        } elseif ($user->isOfficer()) {
            $reports = Report::with('user')->where('department', $user->department)->latest()->get();
            $stats = [
                'total' => $reports->count(),
                'pending' => $reports->where('status', 'Pending')->count(),
                'in_progress' => $reports->where('status', 'In Progress')->count(),
                'resolved' => $reports->where('status', 'Repaired')->count(),
            ];
            return view('admin.dashboard', compact('reports', 'stats', 'announcements', 'departments', 'categories'));
        } else {
            // Citizen - Always show active reports on dashboard
            $allReports = Auth::user()->reports()->latest()->get();
            $reports = $allReports->whereNotIn('status', ['Repaired', 'Rejected']);
            $statusFilter = null;
            
            $stats = [
                'total' => $allReports->count(),
                'pending' => $allReports->whereIn('status', ['Pending', 'In Progress'])->count(),
                'resolved' => $allReports->where('status', 'Repaired')->count(),
            ];

            return view('user.dashboard', compact('reports', 'stats', 'announcements', 'statusFilter', 'departments', 'categories'));
        }
    }

    public function adminReports(Request $request)
    {
        $user = Auth::user();
        $statusFilter = $request->query('status');
        $search = $request->query('search');

        $query = Report::with('user');

        if ($user->isAdmin()) {
            // Admin sees all
        } elseif ($user->isOfficer()) {
            $query->where('department', $user->department);
        } else {
            abort(403);
        }

        // Apply Status Filter
        if ($statusFilter === 'pending') {
            $query->whereIn('status', ['Pending', 'In Progress']);
        } elseif ($statusFilter === 'resolved') {
            $query->where('status', 'Repaired');
        }

        // Apply Search Filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('category', 'LIKE', "%{$search}%")
                  ->orWhere('department', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $reports = $query->latest()->get();

        if ($request->ajax()) {
            return view('admin.reports._report_list', compact('reports'))->render();
        }

        return view('admin.reports.index', compact('reports', 'statusFilter'));
    }

    public function citizenReports(Request $request)
    {
        $allReports = Auth::user()->reports()->latest()->get();
        $statusFilter = $request->query('status');

        if ($statusFilter === 'all') {
            $reports = $allReports;
        } elseif ($statusFilter === 'pending') {
            $reports = $allReports->whereIn('status', ['Pending', 'In Progress']);
        } elseif ($statusFilter === 'resolved') {
            $reports = $allReports->where('status', 'Repaired');
        } else {
            $reports = $allReports;
        }

        return view('reports.index', compact('reports', 'statusFilter'));
    }

    public function update(Request $request, Report $report)
    {
        // Check access
        if (Auth::user()->isOfficer() && $report->department !== Auth::user()->department) {
             abort(403);
        }

        $rules = [
            'status' => 'sometimes|in:Pending,In Progress,Repaired,Rejected',
            'remarks' => 'nullable|string',
            'resolution_photos' => 'nullable|array',
            'resolution_photos.*' => 'image|max:10240',
        ];

        // Strict validation for Officers: Require Photo when marking as Repaired
        if (Auth::user()->isOfficer() && $request->status === 'Repaired') {
            if (!$report->resolution_photo_path && !$request->hasFile('resolution_photos')) {
                return back()->withErrors(['resolution_photos' => 'Officers must upload at least one completed work photo to mark as Repaired.'])->withInput();
            }
        }

        $request->validate($rules);

        if ($request->has('status')) {
            $report->status = $request->status;
        }

        if ($request->has('remarks')) {
            $report->remarks = $request->remarks;
        }

        if ($request->hasFile('resolution_photos')) {
            foreach ($request->file('resolution_photos') as $index => $photo) {
                // Store standard file
                $path = $photo->store('resolution_photos', 'public');
                
                // Read and encode for persistence
                $base64 = base64_encode(file_get_contents($photo->getRealPath()));
                $mime = $photo->getClientMimeType();
                $fullBase64 = "data:$mime;base64,$base64";

                $report->photos()->create([
                    'photo_path' => $path,
                    'photo_content' => $fullBase64,
                    'type' => 'resolution'
                ]);

                // Store first photo in main table for backward compatibility
                if ($index === 0) {
                    $report->update([
                        'resolution_photo_path' => $path,
                        'resolution_photo_content' => $fullBase64,
                    ]);
                }
            }
        }

        $report->save();

        return back()->with('status', 'Report updated successfully!');
    }

    public function destroy(Report $report)
    {
        // Delete all associated photos from storage
        foreach ($report->photos as $photo) {
            if ($photo->photo_path) {
                Storage::disk('public')->delete($photo->photo_path);
            }
        }

        $report->delete();

        return redirect()->route('admin.dashboard')->with('status', 'Report deleted successfully!');
    }

    public function show(Report $report)
    {
        $user = Auth::user();
        
        // Access Control
        $canView = $user->isAdmin() || 
                   ($user->isOfficer() && $user->department === $report->department) || 
                   $user->id === $report->user_id;

        if (!$canView) {
            abort(403);
        }

        return view('reports.show', compact('report'));
    }

    public function support(Report $report)
    {
        $existing = Support::where('user_id', Auth::id())
            ->where('report_id', $report->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $report->decrement('support_count');
            $supported = false;
        } else {
            Support::create([
                'user_id' => Auth::id(),
                'report_id' => $report->id,
            ]);
            $report->increment('support_count');
            $supported = true;
        }

        if (request()->wantsJson()) {
            return response()->json([
                'supported' => $supported,
                'count' => $report->fresh()->support_count,
            ]);
        }
        return back()->with('status', $supported ? 'You supported this report!' : 'Support removed.');
    }

    public function addComment(Request $request, Report $report)
    {
        $request->validate(['content' => 'required|string|max:1000']);
        $report->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'is_internal' => Auth::user()->isOfficer() || Auth::user()->isAdmin(),
        ]);
        return back()->with('status', 'Comment added.');
    }

    public function rateReport(Request $request, Report $report)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'feedback' => 'nullable|string|max:1000',
        ]);
        $report->update([
            'rating' => $request->rating,
            'feedback' => $request->feedback,
        ]);
        return back()->with('status', 'Thank you for your feedback!');
    }

    public function batchUpdate(Request $request)
    {
        $request->validate([
            'report_ids' => 'required|array',
            'status' => 'required|in:Pending,In Progress,Repaired,Rejected',
        ]);
        $query = Report::whereIn('id', $request->report_ids);
        if (Auth::user()->isOfficer()) {
            $query->where('department', Auth::user()->department);
        }
        $query->update(['status' => $request->status]);
        return back()->with('status', 'Selected reports updated.');
    }

    public function mergeReport(Request $request, Report $report)
    {
        $request->validate(['target_report_id' => 'required|exists:reports,id']);
        $report->update([
            'merged_into_id' => $request->target_report_id,
            'status' => 'Rejected',
            'remarks' => 'Merged into case #' . $request->target_report_id . ' as duplicate.',
        ]);
        return back()->with('status', 'Report merged as duplicate.');
    }

    public function markSpam(Report $report)
    {
        $report->update(['is_spam' => true, 'status' => 'Rejected', 'remarks' => 'Flagged as spam.']);
        return back()->with('status', 'Report flagged as spam.');
    }

    public function reassign(Request $request, Report $report)
    {
        $request->validate(['department' => 'required|string']);
        $report->update(['department' => $request->department]);
        return back()->with('status', 'Report reassigned to ' . $request->department);
    }
}
