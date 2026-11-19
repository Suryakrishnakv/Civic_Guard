<?php

namespace App\Services;

use App\Models\Report;
use App\Models\User;
use App\Models\Category;
use App\Models\ReportStatusHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AnalyticsService
{
    /**
     * Compile complete analytics payload for the municipal dashboard.
     */
    public function getDashboardAnalytics(Request $request): array
    {
        $yearFilter = $request->get('year', 'all');
        $monthFilter = $request->get('month', 'all');
        $deptFilter = $request->get('department', 'all');
        $rangeFilter = $request->get('range', 'all');

        // Base query with department and date filters
        $query = Report::query()->with(['user', 'assignedOfficer', 'photos', 'statusHistories']);

        if ($deptFilter && $deptFilter !== 'all') {
            $query->where('department', $deptFilter);
        }

        // Apply Range Filter
        if ($rangeFilter && $rangeFilter !== 'all') {
            switch ($rangeFilter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'this_week':
                    $query->where('created_at', '>=', now()->startOfWeek());
                    break;
                case 'this_month':
                    $query->where('created_at', '>=', now()->startOfMonth());
                    break;
                case 'last_30_days':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
                case 'last_90_days':
                    $query->where('created_at', '>=', now()->subDays(90));
                    break;
                case 'this_year':
                    $query->where('created_at', '>=', now()->startOfYear());
                    break;
            }
        } else {
            // Apply Year & Month filters
            if ($yearFilter && $yearFilter !== 'all' && is_numeric($yearFilter)) {
                $query->whereYear('created_at', (int)$yearFilter);
            }
            if ($monthFilter && $monthFilter !== 'all' && is_numeric($monthFilter)) {
                $query->whereMonth('created_at', (int)$monthFilter);
            }
        }

        $allReports = $query->latest()->get();

        // 1. KPI Cards
        $kpis = $this->calculateKpis($allReports);

        // 2. Department-wise Analytics & Efficiency
        $departmentStats = $this->calculateDepartmentStats($allReports);

        // 3. Monthly & Yearly Trends
        $trendYear = ($yearFilter !== 'all' && is_numeric($yearFilter)) ? (int)$yearFilter : (int)now()->year;
        $monthlyTrend = $this->calculateMonthlyTrend($deptFilter, $trendYear);
        $yearlyTrend = $this->calculateYearlyTrend($deptFilter);

        // 4. Resolution Times
        $resolutionTimeStats = $this->calculateResolutionTimes($allReports);

        // 5. Category-wise Analysis
        $categoryStats = $this->calculateCategoryStats($allReports);

        // 6. Officer Performance and Workload
        $officerStats = $this->calculateOfficerStats();

        // 7. SLA Overdue Summary
        $slaStats = $this->calculateSlaStats($allReports);

        // 8. Recent Activities
        $recentActivities = $this->getRecentActivities();

        // 9. Chart.js Payloads
        $charts = $this->buildChartPayloads($kpis, $departmentStats, $monthlyTrend, $categoryStats);

        // Available Filter Options
        $availableYears = Report::whereNotNull('created_at')
            ->selectRaw('DISTINCT ' . (DB::getDriverName() === 'sqlite' ? "strftime('%Y', created_at)" : "EXTRACT(YEAR FROM created_at)") . ' as year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter()
            ->map(fn($y) => (int)$y)
            ->unique()
            ->values()
            ->toArray();

        if (!in_array(now()->year, $availableYears)) {
            array_unshift($availableYears, now()->year);
        }

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        return [
            'kpis' => $kpis,
            'department_stats' => $departmentStats,
            'monthly_trend' => $monthlyTrend,
            'yearly_trend' => $yearlyTrend,
            'resolution_time_stats' => $resolutionTimeStats,
            'category_stats' => $categoryStats,
            'officer_stats' => $officerStats,
            'sla_stats' => $slaStats,
            'recent_activities' => $recentActivities,
            'charts' => $charts,
            'filters' => [
                'year' => $yearFilter,
                'month' => $monthFilter,
                'department' => $deptFilter,
                'range' => $rangeFilter,
                'available_years' => $availableYears,
                'months' => $months,
                'departments' => User::getDepartments(),
            ],
            'reports_count' => $allReports->count(),
        ];
    }

    /**
     * 1. Calculate KPI Metrics.
     */
    protected function calculateKpis($reports): array
    {
        $total = $reports->count();
        $pending = $reports->where('status', 'Pending')->count();
        $inProgress = $reports->where('status', 'In Progress')->count();
        $repaired = $reports->where('status', 'Repaired')->count();
        $rejected = $reports->where('status', 'Rejected')->count();
        $active = $pending + $inProgress;

        // Resolution efficiency: (Repaired Reports / Total Reports) * 100
        $efficiency = $total > 0 ? round(($repaired / $total) * 100, 1) : 0.0;

        // Ratings if available
        $rated = $reports->whereNotNull('rating');
        $avgRating = $rated->count() > 0 ? round($rated->avg('rating'), 1) : null;

        return [
            'total' => $total,
            'pending' => $pending,
            'in_progress' => $inProgress,
            'repaired' => $repaired,
            'rejected' => $rejected,
            'active' => $active,
            'efficiency' => $efficiency,
            'pending_percent' => $total > 0 ? round(($pending / $total) * 100, 1) : 0,
            'in_progress_percent' => $total > 0 ? round(($inProgress / $total) * 100, 1) : 0,
            'repaired_percent' => $total > 0 ? round(($repaired / $total) * 100, 1) : 0,
            'rejected_percent' => $total > 0 ? round(($rejected / $total) * 100, 1) : 0,
            'avg_rating' => $avgRating,
        ];
    }

    /**
     * 2. Calculate Department-wise Distribution and Resolution Efficiency.
     */
    protected function calculateDepartmentStats($reports): array
    {
        $departments = User::getDepartments();
        $stats = [];
        $grandTotal = $reports->count();

        foreach ($departments as $dept) {
            $deptReports = $reports->where('department', $dept);
            $total = $deptReports->count();
            $repaired = $deptReports->where('status', 'Repaired')->count();
            $pending = $deptReports->where('status', 'Pending')->count();
            $inProgress = $deptReports->where('status', 'In Progress')->count();
            $rejected = $deptReports->where('status', 'Rejected')->count();
            $active = $pending + $inProgress;

            // Efficiency: (Repaired Reports / Total Reports) * 100
            $efficiency = $total > 0 ? round(($repaired / $total) * 100, 1) : 0.0;

            // Department resolution times
            $repairedReports = $deptReports->where('status', 'Repaired');
            $durations = [];
            foreach ($repairedReports as $r) {
                $durations[] = $this->getReportResolutionHours($r);
            }
            $avgHours = count($durations) > 0 ? round(array_sum($durations) / count($durations), 1) : 0;

            $statusLevel = $efficiency >= 70 ? 'High' : ($efficiency >= 40 ? 'Moderate' : 'Low');
            $statusColor = $efficiency >= 70 ? 'emerald' : ($efficiency >= 40 ? 'amber' : 'red');

            $stats[] = [
                'department' => $dept,
                'short_name' => str_replace(' Department', '', $dept),
                'total' => $total,
                'repaired' => $repaired,
                'pending' => $pending,
                'in_progress' => $inProgress,
                'active' => $active,
                'rejected' => $rejected,
                'efficiency' => $efficiency,
                'status_level' => $statusLevel,
                'status_color' => $statusColor,
                'avg_hours' => $avgHours,
                'avg_days' => round($avgHours / 24, 1),
                'share_percentage' => $grandTotal > 0 ? round(($total / $grandTotal) * 100, 1) : 0,
            ];
        }

        // Sort by total descending
        usort($stats, fn($a, $b) => $b['total'] <=> $a['total']);

        return $stats;
    }

    /**
     * 3. Monthly Complaint Trends for selected year.
     */
    protected function calculateMonthlyTrend(?string $department, int $year): array
    {
        $query = Report::query()->whereYear('created_at', $year);
        if ($department && $department !== 'all') {
            $query->where('department', $department);
        }

        $reports = $query->get();

        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $filed = array_fill(1, 12, 0);
        $repaired = array_fill(1, 12, 0);
        $pending = array_fill(1, 12, 0);

        foreach ($reports as $report) {
            $m = (int)$report->created_at->format('n');
            $filed[$m]++;
            if ($report->status === 'Repaired') {
                $repaired[$m]++;
            } elseif (in_array($report->status, ['Pending', 'In Progress'])) {
                $pending[$m]++;
            }
        }

        return [
            'year' => $year,
            'labels' => $monthNames,
            'filed' => array_values($filed),
            'repaired' => array_values($repaired),
            'pending' => array_values($pending),
        ];
    }

    /**
     * 3b. Yearly Complaint Trends.
     */
    protected function calculateYearlyTrend(?string $department): array
    {
        $currentYear = (int)now()->year;
        $years = [$currentYear - 2, $currentYear - 1, $currentYear];

        $query = Report::query()->whereIn(DB::raw((DB::getDriverName() === 'sqlite' ? "strftime('%Y', created_at)" : "EXTRACT(YEAR FROM created_at)")), $years);
        if ($department && $department !== 'all') {
            $query->where('department', $department);
        }

        $allReports = Report::all();
        $yearlyData = [];

        foreach ($years as $yr) {
            $yrReports = $allReports->filter(fn($r) => $r->created_at && (int)$r->created_at->format('Y') === $yr);
            $total = $yrReports->count();
            $repaired = $yrReports->where('status', 'Repaired')->count();
            $efficiency = $total > 0 ? round(($repaired / $total) * 100, 1) : 0.0;

            $yearlyData[] = [
                'year' => $yr,
                'total' => $total,
                'repaired' => $repaired,
                'efficiency' => $efficiency,
            ];
        }

        return $yearlyData;
    }

    /**
     * 4. Average Complaint Resolution Time.
     */
    protected function calculateResolutionTimes($reports): array
    {
        $repairedReports = $reports->where('status', 'Repaired');
        $durations = [];

        foreach ($repairedReports as $report) {
            $durations[] = $this->getReportResolutionHours($report);
        }

        $count = count($durations);
        $avgHours = $count > 0 ? round(array_sum($durations) / $count, 1) : 0.0;
        $avgDays = round($avgHours / 24, 1);
        $minHours = $count > 0 ? round(min($durations), 1) : 0.0;
        $maxHours = $count > 0 ? round(max($durations), 1) : 0.0;

        $formatted = $avgHours < 24 
            ? ($avgHours . ' Hours') 
            : ($avgDays . ' Days (' . round($avgHours) . ' hrs)');

        return [
            'count_resolved' => $count,
            'avg_hours' => $avgHours,
            'avg_days' => $avgDays,
            'min_hours' => $minHours,
            'max_hours' => $maxHours,
            'formatted' => $formatted,
        ];
    }

    /**
     * Helper to compute resolution hours for an individual report.
     */
    public function getReportResolutionHours(Report $report): float
    {
        $resolvedAt = null;

        // Try status history first
        if ($report->relationLoaded('statusHistories') || $report->statusHistories()->exists()) {
            $history = $report->statusHistories->firstWhere('status', 'Repaired');
            if ($history && $history->created_at) {
                $resolvedAt = $history->created_at;
            }
        }

        if (!$resolvedAt && $report->updated_at && $report->status === 'Repaired') {
            $resolvedAt = $report->updated_at;
        }

        if ($resolvedAt && $report->created_at) {
            $diffMinutes = $report->created_at->diffInMinutes($resolvedAt);
            return max(round($diffMinutes / 60, 1), 0.5);
        }

        return 4.0; // default baseline for repaired mock/demo
    }

    /**
     * 5. Category-wise Complaint Analysis.
     */
    protected function calculateCategoryStats($reports): array
    {
        $categoriesMeta = Category::all()->keyBy('name');
        $grouped = $reports->groupBy('category');
        $categoryStats = [];

        foreach ($grouped as $catName => $items) {
            $total = $items->count();
            $repaired = $items->where('status', 'Repaired')->count();
            $pending = $items->where('status', 'Pending')->count();
            $inProgress = $items->where('status', 'In Progress')->count();
            $rejected = $items->where('status', 'Rejected')->count();
            $efficiency = $total > 0 ? round(($repaired / $total) * 100, 1) : 0.0;

            $meta = $categoriesMeta[$catName] ?? null;
            $severity = $meta ? $meta->severity : 'Medium';
            $slaDays = $meta ? $meta->sla_days : 3;

            $categoryStats[] = [
                'name' => $catName,
                'department' => $items->first()->department ?? ($meta ? $meta->department : 'General'),
                'total' => $total,
                'repaired' => $repaired,
                'pending' => $pending,
                'in_progress' => $inProgress,
                'rejected' => $rejected,
                'efficiency' => $efficiency,
                'severity' => $severity,
                'sla_days' => $slaDays,
            ];
        }

        // Sort by total complaints descending
        usort($categoryStats, fn($a, $b) => $b['total'] <=> $a['total']);

        return $categoryStats;
    }

    /**
     * 6. Officer Performance and Workload Statistics.
     */
    protected function calculateOfficerStats(): array
    {
        $officers = User::where('role', User::ROLE_OFFICER)->with('assignedReports')->get();
        $stats = [];

        foreach ($officers as $officer) {
            $assigned = $officer->assignedReports;
            $totalAssigned = $assigned->count();

            // If direct assignment has 0, fallback to check department reports
            if ($totalAssigned === 0 && $officer->department) {
                $assigned = Report::where('department', $officer->department)->get();
                $totalAssigned = $assigned->count();
            }

            $repaired = $assigned->where('status', 'Repaired')->count();
            $pending = $assigned->where('status', 'Pending')->count();
            $inProgress = $assigned->where('status', 'In Progress')->count();
            $rejected = $assigned->where('status', 'Rejected')->count();
            $active = $pending + $inProgress;

            // Efficiency
            $efficiency = $totalAssigned > 0 ? round(($repaired / $totalAssigned) * 100, 1) : 0.0;

            // Workload status
            if ($active > 10) {
                $workload = 'High Workload';
                $workloadColor = 'red';
            } elseif ($active >= 3) {
                $workload = 'Moderate Workload';
                $workloadColor = 'amber';
            } else {
                $workload = 'Optimal';
                $workloadColor = 'emerald';
            }

            // Officer average citizen rating
            $ratings = $assigned->whereNotNull('rating')->pluck('rating');
            $avgRating = $ratings->count() > 0 ? round($ratings->avg(), 1) : null;

            $stats[] = [
                'id' => $officer->id,
                'name' => $officer->name,
                'email' => $officer->email,
                'department' => $officer->department,
                'total_assigned' => $totalAssigned,
                'repaired' => $repaired,
                'pending' => $pending,
                'in_progress' => $inProgress,
                'active' => $active,
                'rejected' => $rejected,
                'efficiency' => $efficiency,
                'workload' => $workload,
                'workload_color' => $workloadColor,
                'avg_rating' => $avgRating,
            ];
        }

        // Sort by total assigned descending
        usort($stats, fn($a, $b) => $b['total_assigned'] <=> $a['total_assigned']);

        return $stats;
    }

    /**
     * 7. SLA Overdue Complaint Summary.
     */
    protected function calculateSlaStats($reports): array
    {
        $activeReports = $reports->whereIn('status', ['Pending', 'In Progress']);
        $totalActive = $activeReports->count();
        $categoriesMeta = Category::all()->keyBy('name');

        $overdueList = [];
        $overdueCount = 0;
        $warningCount = 0;
        $onTrackCount = 0;
        $overdueByDept = [];

        foreach ($activeReports as $r) {
            $slaDays = $categoriesMeta[$r->category]->sla_days ?? 3;
            $deadline = $r->sla_deadline ? Carbon::parse($r->sla_deadline) : $r->created_at->copy()->addDays($slaDays);

            $isOverdue = now()->gt($deadline);
            $hoursDiff = round(now()->diffInHours($deadline, false)); // negative if overdue

            if ($isOverdue) {
                $overdueCount++;
                $dept = $r->department ?? 'General';
                $overdueByDept[$dept] = ($overdueByDept[$dept] ?? 0) + 1;

                $overdueList[] = [
                    'id' => $r->id,
                    'case_number' => '#' . str_pad($r->id, 5, '0', STR_PAD_LEFT),
                    'category' => $r->category,
                    'department' => $r->department,
                    'status' => $r->status,
                    'reporter_name' => $r->user ? $r->user->name : 'Citizen',
                    'created_at' => $r->created_at ? $r->created_at->format('M d, Y') : '',
                    'deadline' => $deadline->format('M d, Y'),
                    'hours_overdue' => abs($hoursDiff),
                    'days_overdue' => round(abs($hoursDiff) / 24, 1),
                    'assigned_officer' => $r->assignedOfficer ? $r->assignedOfficer->name : 'Unassigned',
                ];
            } elseif (now()->addDay()->gt($deadline)) {
                $warningCount++;
            } else {
                $onTrackCount++;
            }
        }

        // Sort overdue list by most overdue first
        usort($overdueList, fn($a, $b) => $b['hours_overdue'] <=> $a['hours_overdue']);

        $overduePercentage = $totalActive > 0 ? round(($overdueCount / $totalActive) * 100, 1) : 0.0;
        $complianceRate = $totalActive > 0 ? round((($totalActive - $overdueCount) / $totalActive) * 100, 1) : 100.0;

        return [
            'total_active' => $totalActive,
            'overdue_count' => $overdueCount,
            'warning_count' => $warningCount,
            'on_track_count' => $onTrackCount,
            'overdue_percentage' => $overduePercentage,
            'compliance_rate' => $complianceRate,
            'overdue_by_dept' => $overdueByDept,
            'overdue_list' => array_slice($overdueList, 0, 10),
            'total_overdue_list_count' => count($overdueList),
        ];
    }

    /**
     * 8. Recent Complaint Activities and Status Updates.
     */
    protected function getRecentActivities(): array
    {
        $histories = ReportStatusHistory::with(['report.user', 'changer'])
            ->latest()
            ->take(12)
            ->get();

        $activities = [];

        foreach ($histories as $h) {
            $report = $h->report;
            if (!$report) continue;

            $action = 'Status Updated';
            if ($h->status === 'Pending') {
                $action = 'Complaint Filed';
            } elseif ($h->status === 'In Progress') {
                $action = 'Investigation Started';
            } elseif ($h->status === 'Repaired') {
                $action = 'Marked as Repaired';
            } elseif ($h->status === 'Rejected') {
                $action = 'Complaint Rejected';
            }

            $activities[] = [
                'id' => $h->id,
                'report_id' => $report->id,
                'case_number' => '#' . str_pad($report->id, 5, '0', STR_PAD_LEFT),
                'action' => $action,
                'status' => $h->status,
                'category' => $report->category,
                'department' => $report->department,
                'remarks' => $h->remarks ?: 'No remarks recorded.',
                'actor' => $h->changer ? $h->changer->name : 'System Automated Dispatch',
                'time' => $h->created_at ? $h->created_at->diffForHumans() : 'Recently',
                'created_at' => $h->created_at ? $h->created_at->format('M d, Y h:i A') : '',
            ];
        }

        // If histories are few, also include latest report creation
        if (count($activities) < 5) {
            $latestReports = Report::with('user')->latest()->take(6)->get();
            foreach ($latestReports as $rep) {
                $activities[] = [
                    'id' => 'r-' . $rep->id,
                    'report_id' => $rep->id,
                    'case_number' => '#' . str_pad($rep->id, 5, '0', STR_PAD_LEFT),
                    'action' => 'Report Submitted',
                    'status' => $rep->status,
                    'category' => $rep->category,
                    'department' => $rep->department,
                    'remarks' => Str::limit($rep->description, 70),
                    'actor' => $rep->user ? $rep->user->name : 'Citizen',
                    'time' => $rep->created_at ? $rep->created_at->diffForHumans() : 'Recently',
                    'created_at' => $rep->created_at ? $rep->created_at->format('M d, Y h:i A') : '',
                ];
            }
        }

        return array_slice($activities, 0, 10);
    }

    /**
     * 9. Build Chart.js Formatted Payloads.
     */
    protected function buildChartPayloads(array $kpis, array $departmentStats, array $monthlyTrend, array $categoryStats): array
    {
        // Status Doughnut Chart
        $statusChart = [
            'labels' => ['Pending Review', 'In Progress', 'Repaired / Resolved', 'Rejected'],
            'data' => [$kpis['pending'], $kpis['in_progress'], $kpis['repaired'], $kpis['rejected']],
            'colors' => ['#f59e0b', '#3b82f6', '#10b981', '#64748b'],
        ];

        // Department Distribution & Efficiency Chart
        $deptLabels = array_column($departmentStats, 'short_name');
        $deptTotals = array_column($departmentStats, 'total');
        $deptRepaired = array_column($departmentStats, 'repaired');
        $deptPending = array_column($departmentStats, 'active');
        $deptEfficiencies = array_column($departmentStats, 'efficiency');

        $departmentChart = [
            'labels' => $deptLabels,
            'totals' => $deptTotals,
            'repaired' => $deptRepaired,
            'active' => $deptPending,
            'efficiency' => $deptEfficiencies,
        ];

        // Monthly Trend Chart
        $trendChart = [
            'labels' => $monthlyTrend['labels'],
            'filed' => $monthlyTrend['filed'],
            'repaired' => $monthlyTrend['repaired'],
            'pending' => $monthlyTrend['pending'],
        ];

        // Category Breakdown Chart (Top 7 categories)
        $topCategories = array_slice($categoryStats, 0, 7);
        $categoryChart = [
            'labels' => array_column($topCategories, 'name'),
            'totals' => array_column($topCategories, 'total'),
            'repaired' => array_column($topCategories, 'repaired'),
        ];

        return [
            'status' => $statusChart,
            'department' => $departmentChart,
            'trend' => $trendChart,
            'category' => $categoryChart,
        ];
    }
}
