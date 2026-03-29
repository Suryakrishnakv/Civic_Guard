<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year');
        $month = $request->get('month');
        $filter = $request->get('filter'); // Keep for legacy links like 'this_month' or 'all'
        
        $departments = User::getDepartments();
        $query = Report::query();

        // Handle Legacy Filters
        if ($filter === 'this_month') {
            $year = now()->year;
            $month = now()->month;
            $filter = 'custom';
        } elseif ($filter === 'all') {
            $year = null;
            $month = null;
        }

        // Apply Year Filter
        if ($year) {
            $query->whereYear('created_at', $year);
        }

        // Apply Month Filter
        if ($month) {
            $query->whereMonth('created_at', $month);
        }

        // Get available years for the filter
        $availableYears = Report::selectRaw('EXTRACT(YEAR FROM created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // Months List
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        $reports = $query->get();
        $evaluations = [];

        foreach ($departments as $dept) {
            $deptReports = $reports->where('department', $dept);
            $total = $deptReports->count();
            $resolved = $deptReports->where('status', 'Repaired')->count();
            $pending = $deptReports->whereIn('status', ['Pending', 'In Progress'])->count();
            $rejected = $deptReports->where('status', 'Rejected')->count();

            $efficiency = $total > 0 ? round(($resolved / $total) * 100, 1) : 0;

            $evaluations[] = [
                'name' => $dept,
                'total' => $total,
                'resolved' => $resolved,
                'pending' => $pending,
                'rejected' => $rejected,
                'efficiency' => $efficiency,
            ];
        }

        // Sort by efficiency descending
        usort($evaluations, function($a, $b) {
            return $b['efficiency'] <=> $a['efficiency'];
        });

        $stats = [
            'total_received' => $reports->count(),
            'total_resolved' => $reports->where('status', 'Repaired')->count(),
            'avg_efficiency' => count($evaluations) > 0 ? round(array_sum(array_column($evaluations, 'efficiency')) / count($evaluations), 1) : 0,
            'current_year' => $year,
            'current_month' => $month,
            'available_years' => $availableYears,
            'months' => $months,
            'filter_type' => $filter ?? ($year || $month ? 'custom' : 'all')
        ];

        return view('admin.evaluations', compact('evaluations', 'stats'));
    }
}
