<?php

namespace App\Http\Controllers;

use App\Models\Ward;
use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WardController extends Controller
{
    /**
     * Display a listing of municipal wards with KPI statistics and Leaflet GIS overview.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isOfficer()) {
            abort(403, 'Unauthorized. Ward Management is restricted to Administrators and Municipal Officers.');
        }

        $query = Ward::with(['officers', 'reports']);

        // Search query
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('ward_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $wards = $query->orderBy('ward_number')->get();

        // Calculate aggregated KPI statistics
        $totalWards = $wards->count();
        $totalReports = Report::whereNotNull('ward_id')->count();
        $totalRepaired = Report::whereNotNull('ward_id')->where('status', 'Repaired')->count();
        $totalPending = Report::whereNotNull('ward_id')->where('status', 'Pending')->count();
        $totalInProgress = Report::whereNotNull('ward_id')->where('status', 'In Progress')->count();
        $overallEfficiency = $totalReports > 0 ? round(($totalRepaired / $totalReports) * 100, 1) : 0.0;

        // Top complaint ward
        $topWard = $wards->sortByDesc(fn ($w) => $w->reports->count())->first();

        // Build GeoJSON features for Leaflet overview
        $geoJsonFeatures = $wards->map(function ($ward) {
            $reportsCount = $ward->reports->count();
            $repairedCount = $ward->reports->where('status', 'Repaired')->count();
            $efficiency = $reportsCount > 0 ? round(($repairedCount / $reportsCount) * 100, 1) : 0;

            return [
                'type' => 'Feature',
                'properties' => [
                    'id' => $ward->id,
                    'ward_number' => $ward->ward_number,
                    'name' => $ward->name,
                    'description' => $ward->description,
                    'center_lat' => $ward->center_lat,
                    'center_lng' => $ward->center_lng,
                    'total_reports' => $reportsCount,
                    'repaired_reports' => $repairedCount,
                    'efficiency' => $efficiency,
                    'officers' => $ward->officers->pluck('name')->toArray(),
                    'show_url' => route('admin.wards.show', $ward->id),
                ],
                'geometry' => [
                    'type' => 'Polygon',
                    'coordinates' => [
                        array_map(fn ($p) => [(float)$p[1], (float)$p[0]], $ward->boundaries ?? [])
                    ],
                ],
            ];
        });

        return view('admin.wards.index', compact(
            'wards',
            'totalWards',
            'totalReports',
            'totalRepaired',
            'totalPending',
            'totalInProgress',
            'overallEfficiency',
            'topWard',
            'geoJsonFeatures'
        ));
    }

    /**
     * Show the form for creating a new municipal ward with interactive boundary drawer.
     */
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized. Only Administrators can create municipal wards.');
        }

        $officers = User::where('role', User::ROLE_OFFICER)->orderBy('name')->get();
        $nextWardNumber = (Ward::max('ward_number') ?? 0) + 1;

        return view('admin.wards.create', compact('officers', 'nextWardNumber'));
    }

    /**
     * Store a newly created municipal ward in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        // Process boundaries if passed as JSON string
        $boundaries = $request->boundaries;
        if (is_string($boundaries)) {
            $boundaries = json_decode($boundaries, true);
        }

        $request->merge(['boundaries_array' => $boundaries]);

        $request->validate([
            'ward_number' => 'required|integer|min:1|unique:wards,ward_number',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'boundaries_array' => 'required|array|min:3',
            'boundaries_array.*' => 'required|array|size:2',
            'boundaries_array.*.0' => 'required|numeric|between:-90,90',
            'boundaries_array.*.1' => 'required|numeric|between:-180,180',
            'officers' => 'nullable|array',
            'officers.*' => 'exists:users,id',
        ], [
            'boundaries_array.min' => 'Ward boundary polygon must have at least 3 coordinate points.',
        ]);

        $centroid = Ward::calculateCentroid($boundaries);

        $ward = Ward::create([
            'ward_number' => $request->ward_number,
            'name' => $request->name,
            'description' => $request->description,
            'boundaries' => $boundaries,
            'center_lat' => $centroid['lat'],
            'center_lng' => $centroid['lng'],
        ]);

        if ($request->has('officers')) {
            $ward->officers()->sync($request->officers);
        }

        // Auto-assign existing orphaned reports within this ward's polygon
        $reportsWithoutWard = Report::whereNull('ward_id')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        foreach ($reportsWithoutWard as $rep) {
            if ($ward->containsPoint((float)$rep->latitude, (float)$rep->longitude)) {
                $rep->update(['ward_id' => $ward->id]);
            }
        }

        return redirect()->route('admin.wards.index')->with('status', "Ward #{$ward->ward_number} ({$ward->name}) created successfully!");
    }

    /**
     * Display the specified ward with detailed GIS map, complaints table and officer workload.
     */
    public function show(Ward $ward, Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isOfficer()) {
            abort(403, 'Unauthorized.');
        }

        $ward->load('officers');

        $reportsQuery = $ward->reports()->with(['user', 'assignedOfficer', 'photos']);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $reportsQuery->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $reportsQuery->where('category', $request->category);
        }

        // Search within ward
        if ($request->filled('search')) {
            $s = $request->search;
            $reportsQuery->where(function ($q) use ($s) {
                $q->where('description', 'like', "%{$s}%")
                  ->orWhere('location', 'like', "%{$s}%")
                  ->orWhere('id', 'like', "%{$s}%");
            });
        }

        $reports = $reportsQuery->latest()->paginate(12)->withQueryString();

        // Ward Performance Statistics
        $totalCount = $ward->reports()->count();
        $pendingCount = $ward->reports()->where('status', 'Pending')->count();
        $inProgressCount = $ward->reports()->where('status', 'In Progress')->count();
        $repairedCount = $ward->reports()->where('status', 'Repaired')->count();
        $rejectedCount = $ward->reports()->where('status', 'Rejected')->count();
        $efficiency = $totalCount > 0 ? round(($repairedCount / $totalCount) * 100, 1) : 0.0;

        // SLA Overdue complaints in ward
        $overdueCount = $ward->reports()
            ->whereIn('status', ['Pending', 'In Progress'])
            ->whereNotNull('sla_deadline')
            ->where('sla_deadline', '<', now())
            ->count();

        // Average resolution time in hours for this ward
        $repairedReports = $ward->reports()->where('status', 'Repaired')->get();
        $avgResolutionHours = 0;
        if ($repairedReports->isNotEmpty()) {
            $totalHours = $repairedReports->sum(function ($r) {
                return $r->created_at->diffInHours($r->updated_at);
            });
            $avgResolutionHours = round($totalHours / $repairedReports->count(), 1);
        }

        // Map reports for Leaflet markers & heatmap
        $mapReports = $ward->reports()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['photos', 'user'])
            ->get()
            ->map(function ($r) {
                $photoUrl = null;
                if ($r->photos->isNotEmpty()) {
                    $firstPhoto = $r->photos->first();
                    $photoUrl = $firstPhoto->photo_content ?: asset('storage/' . $firstPhoto->photo_path);
                } elseif ($r->photo_content) {
                    $photoUrl = $r->photo_content;
                } elseif ($r->photo_path) {
                    $photoUrl = asset('storage/' . $r->photo_path);
                }

                return [
                    'id' => $r->id,
                    'category' => $r->category,
                    'department' => $r->department,
                    'description' => $r->description,
                    'status' => $r->status,
                    'location' => $r->location,
                    'latitude' => (float)$r->latitude,
                    'longitude' => (float)$r->longitude,
                    'created_at' => $r->created_at->format('M d, Y h:i A'),
                    'photo' => $photoUrl,
                    'url' => route('reports.show', $r->id),
                ];
            });

        $categories = array_keys(Report::getCategoryDepartmentMap());

        return view('admin.wards.show', compact(
            'ward',
            'reports',
            'mapReports',
            'totalCount',
            'pendingCount',
            'inProgressCount',
            'repairedCount',
            'rejectedCount',
            'efficiency',
            'overdueCount',
            'avgResolutionHours',
            'categories'
        ));
    }

    /**
     * Show the form for editing the specified municipal ward.
     */
    public function edit(Ward $ward)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized. Only Administrators can edit municipal wards.');
        }

        $officers = User::where('role', User::ROLE_OFFICER)->orderBy('name')->get();
        $assignedOfficerIds = $ward->officers->pluck('id')->toArray();

        return view('admin.wards.edit', compact('ward', 'officers', 'assignedOfficerIds'));
    }

    /**
     * Update the specified municipal ward in storage.
     */
    public function update(Request $request, Ward $ward)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $boundaries = $request->boundaries;
        if (is_string($boundaries)) {
            $boundaries = json_decode($boundaries, true);
        }

        $request->merge(['boundaries_array' => $boundaries]);

        $request->validate([
            'ward_number' => "required|integer|min:1|unique:wards,ward_number,{$ward->id}",
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'boundaries_array' => 'required|array|min:3',
            'boundaries_array.*' => 'required|array|size:2',
            'boundaries_array.*.0' => 'required|numeric|between:-90,90',
            'boundaries_array.*.1' => 'required|numeric|between:-180,180',
            'officers' => 'nullable|array',
            'officers.*' => 'exists:users,id',
        ]);

        $centroid = Ward::calculateCentroid($boundaries);

        $ward->update([
            'ward_number' => $request->ward_number,
            'name' => $request->name,
            'description' => $request->description,
            'boundaries' => $boundaries,
            'center_lat' => $centroid['lat'],
            'center_lng' => $centroid['lng'],
        ]);

        if ($request->has('officers')) {
            $ward->officers()->sync($request->officers);
        } else {
            $ward->officers()->detach();
        }

        // Re-evaluate reports associated with this ward
        Report::where('ward_id', $ward->id)->get()->each(function ($rep) use ($ward) {
            if (!$ward->containsPoint((float)$rep->latitude, (float)$rep->longitude)) {
                $newWard = Ward::findWardByCoordinates((float)$rep->latitude, (float)$rep->longitude);
                $rep->update(['ward_id' => $newWard ? $newWard->id : null]);
            }
        });

        return redirect()->route('admin.wards.index')->with('status', "Ward #{$ward->ward_number} updated successfully!");
    }

    /**
     * Remove the specified municipal ward from storage.
     */
    public function destroy(Ward $ward)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $wardNumber = $ward->ward_number;
        $wardName = $ward->name;

        // Disassociate reports
        Report::where('ward_id', $ward->id)->update(['ward_id' => null]);
        $ward->officers()->detach();
        $ward->delete();

        return redirect()->route('admin.wards.index')->with('status', "Ward #{$wardNumber} ({$wardName}) deleted successfully.");
    }

    /**
     * AJAX Endpoint: Automatically identify the ward from GPS coordinates.
     * Also returns nearby reports in the same ward to prevent duplicates and inform citizens.
     */
    public function detectWard(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $lat = (float) $request->latitude;
        $lng = (float) $request->longitude;

        $ward = Ward::findWardByCoordinates($lat, $lng);

        if (!$ward) {
            return response()->json([
                'found' => false,
                'message' => 'Coordinates fall outside defined municipal ward boundaries.',
            ]);
        }

        $totalReports = $ward->reports()->count();
        $repairedReports = $ward->reports()->where('status', 'Repaired')->count();
        $pendingReports = $ward->reports()->where('status', 'Pending')->count();
        $inProgressReports = $ward->reports()->where('status', 'In Progress')->count();
        $efficiency = $totalReports > 0 ? round(($repairedReports / $totalReports) * 100, 1) : 0.0;

        // Fetch recent active reports in this same ward
        $nearbyWardReports = $ward->reports()
            ->whereIn('status', ['Pending', 'In Progress'])
            ->latest()
            ->take(5)
            ->get(['id', 'category', 'status', 'location', 'created_at']);

        return response()->json([
            'found' => true,
            'ward' => [
                'id' => $ward->id,
                'ward_number' => $ward->ward_number,
                'name' => $ward->name,
                'description' => $ward->description,
                'center_lat' => $ward->center_lat,
                'center_lng' => $ward->center_lng,
                'total_reports' => $totalReports,
                'pending_reports' => $pendingReports,
                'in_progress_reports' => $inProgressReports,
                'repaired_reports' => $repairedReports,
                'resolution_efficiency' => $efficiency,
            ],
            'nearby_ward_reports' => $nearbyWardReports,
            'message' => "Detected Ward #{$ward->ward_number}: {$ward->name}",
        ]);
    }

    /**
     * Export Municipal Wards Analytics to Excel / CSV with UTF-8 BOM.
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isOfficer()) {
            abort(403, 'Unauthorized.');
        }

        $timestamp = now()->format('Y-m-d_His');
        $fileName = "CivicGuard_Ward_Analytics_{$timestamp}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');

            // Write UTF-8 BOM for Microsoft Excel compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header
            fputcsv($handle, ['CIVICGUARD MUNICIPAL WARD-BASED ANALYTICS REPORT']);
            fputcsv($handle, ['Generated At', now()->format('Y-m-d H:i:s')]);
            fputcsv($handle, ['Generated By', Auth::user()->name . ' (' . ucfirst(Auth::user()->role) . ')']);
            fputcsv($handle, []);

            // Columns
            fputcsv($handle, [
                'Ward Number',
                'Ward Name',
                'Assigned Officers',
                'Total Complaints',
                'Pending Review',
                'In Progress',
                'Repaired / Resolved',
                'Rejected',
                'Resolution Efficiency (%)',
                'Centroid Latitude',
                'Centroid Longitude',
                'Description'
            ]);

            $wards = Ward::with(['officers', 'reports'])->orderBy('ward_number')->get();

            foreach ($wards as $ward) {
                $total = $ward->reports->count();
                $pending = $ward->reports->where('status', 'Pending')->count();
                $inProgress = $ward->reports->where('status', 'In Progress')->count();
                $repaired = $ward->reports->where('status', 'Repaired')->count();
                $rejected = $ward->reports->where('status', 'Rejected')->count();
                $efficiency = $total > 0 ? round(($repaired / $total) * 100, 1) : 0;
                $officers = $ward->officers->pluck('name')->implode(', ') ?: 'None Assigned';

                fputcsv($handle, [
                    'Ward #' . $ward->ward_number,
                    $ward->name,
                    $officers,
                    $total,
                    $pending,
                    $inProgress,
                    $repaired,
                    $rejected,
                    $efficiency . '%',
                    $ward->center_lat,
                    $ward->center_lng,
                    $ward->description,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export Municipal Wards Overview to Printable Executive PDF document.
     */
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isOfficer()) {
            abort(403, 'Unauthorized.');
        }

        $wards = Ward::with(['officers', 'reports'])->orderBy('ward_number')->get();
        $totalReports = Report::whereNotNull('ward_id')->count();
        $totalRepaired = Report::whereNotNull('ward_id')->where('status', 'Repaired')->count();
        $overallEfficiency = $totalReports > 0 ? round(($totalRepaired / $totalReports) * 100, 1) : 0;

        $autoPrint = $request->has('print');

        return view('admin.wards.pdf', compact('wards', 'totalReports', 'totalRepaired', 'overallEfficiency', 'autoPrint'));
    }

    /**
     * Generate printable Ward Inspection Report for Municipal Officers.
     */
    public function inspectionReport(Ward $ward, Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isOfficer()) {
            abort(403, 'Unauthorized.');
        }

        $ward->load('officers');

        $activeReports = $ward->reports()
            ->whereIn('status', ['Pending', 'In Progress'])
            ->with(['user', 'assignedOfficer'])
            ->latest()
            ->get();

        $repairedRecent = $ward->reports()
            ->where('status', 'Repaired')
            ->latest()
            ->take(10)
            ->get();

        $autoPrint = $request->has('print');

        return view('admin.wards.inspection-pdf', compact('ward', 'activeReports', 'repairedRecent', 'autoPrint'));
    }
}
