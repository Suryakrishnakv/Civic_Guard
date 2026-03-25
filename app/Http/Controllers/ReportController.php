<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'location' => 'required|string',
            'photo' => 'required|image|max:10240', // Mandatory Photo
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('reports', 'public');
        }

        $map = Report::getCategoryDepartmentMap();
        $department = $map[$request->category] ?? null;

        Report::create([
            'user_id' => Auth::id(),
            'type' => 'Damage', // Default type
            'category' => $request->category,
            'department' => $department,
            'description' => $request->description,
            'location' => $request->location,
            'photo_path' => $photoPath,
            'status' => 'Pending',
        ]);

        return redirect()->route('dashboard')->with('status', 'Report submitted successfully!');
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

        if ($user->isAdmin()) {
            $reports = Report::with('user')->latest()->get();
            $stats = [
                'total_users' => \App\Models\User::where('role', \App\Models\User::ROLE_CITIZEN)->count(),
                'total_officers' => \App\Models\User::where('role', \App\Models\User::ROLE_OFFICER)->count(),
                'total' => Report::count(),
                'pending' => Report::where('status', 'Pending')->count(),
                'in_progress' => Report::where('status', 'In Progress')->count(),
                'resolved' => Report::where('status', 'Repaired')->count(),
            ];
            $sentBroadcasts = \App\Models\Announcement::where('user_id', $user->id)->latest()->get();
            return view('admin.dashboard', compact('reports', 'stats', 'announcements', 'sentBroadcasts'));
        } elseif ($user->isOfficer()) {
            $reports = Report::with('user')->where('department', $user->department)->latest()->get();
            $stats = [
                'total' => $reports->count(),
                'pending' => $reports->where('status', 'Pending')->count(),
                'in_progress' => $reports->where('status', 'In Progress')->count(),
                'resolved' => $reports->where('status', 'Repaired')->count(),
            ];
            return view('admin.dashboard', compact('reports', 'stats', 'announcements'));
        } else {
            // Citizen - Always show active reports on dashboard
            $allReports = Auth::user()->reports()->latest()->get();
            $reports = $allReports->whereNotIn('status', ['Repaired', 'Rejected']);
            $statusFilter = null; // Dashboard doesn't filter locally anymore
            
            $stats = [
                'total' => $allReports->count(),
                'pending' => $allReports->whereIn('status', ['Pending', 'In Progress'])->count(),
                'resolved' => $allReports->where('status', 'Repaired')->count(),
            ];

            return view('user.dashboard', compact('reports', 'stats', 'announcements', 'statusFilter'));
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
            // Default: show all if no specific filter, or maybe default to active
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
            'priority' => 'sometimes|in:Low,Medium,High,Critical',
            'remarks' => 'nullable|string',
            'resolution_photo' => 'nullable|image|max:10240', // Base rule
        ];

        // Strict validation for Officers: Require Photo when marking as Repaired
        if (Auth::user()->isOfficer() && $request->status === 'Repaired') {
            if (!$report->resolution_photo_path && !$request->hasFile('resolution_photo')) {
                return back()->withErrors(['resolution_photo' => 'Officers must upload a completed work photo to mark as Repaired.'])->withInput();
            }
        }

        $request->validate($rules);

        if ($request->has('status')) {
            $report->status = $request->status;
        }

        if ($request->has('priority')) {
            $report->priority = $request->priority;
        }

        if ($request->has('remarks')) {
            $report->remarks = $request->remarks;
        }

        if ($request->hasFile('resolution_photo')) {
            $path = $request->file('resolution_photo')->store('resolution_photos', 'public');
            $report->resolution_photo_path = $path;
        }

        $report->save();

        return back()->with('status', 'Report updated successfully!');
    }

    public function destroy(Report $report)
    {
        if ($report->photo_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($report->photo_path);
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
}
