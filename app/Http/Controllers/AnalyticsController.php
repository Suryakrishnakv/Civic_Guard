<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticsController extends Controller
{
    protected AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Display the Advanced Municipal Analytics Dashboard.
     */
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized. Advanced Municipal Analytics is restricted to Administrators.');
        }

        $analytics = $this->analyticsService->getDashboardAnalytics($request);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $analytics,
            ]);
        }

        return view('admin.analytics.index', compact('analytics'));
    }

    /**
     * AJAX endpoint for live filter updates.
     */
    public function ajaxData(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $analytics = $this->analyticsService->getDashboardAnalytics($request);
        return response()->json([
            'success' => true,
            'analytics' => $analytics,
        ]);
    }

    /**
     * Export Municipal Analytics to Microsoft Excel (.csv with UTF-8 BOM).
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $analytics = $this->analyticsService->getDashboardAnalytics($request);
        $timestamp = now()->format('Y-m-d_His');
        $fileName = "CivicGuard_Municipal_Analytics_{$timestamp}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($analytics) {
            $handle = fopen('php://output', 'w');

            // Write UTF-8 BOM for Microsoft Excel compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // 1. Executive Municipal Header
            fputcsv($handle, ['CIVICGUARD MUNICIPAL ANALYTICS REPORT']);
            fputcsv($handle, ['Generated At', now()->format('Y-m-d H:i:s')]);
            fputcsv($handle, ['Reporting Officer', Auth::user()->name . ' (' . Auth::user()->email . ')']);
            fputcsv($handle, ['Scope', 'City Municipal Command Center']);
            fputcsv($handle, []);

            // 2. Executive Key Performance Indicators (KPIs)
            fputcsv($handle, ['--- SECTION 1: KEY PERFORMANCE INDICATORS (KPIs) ---']);
            fputcsv($handle, ['Metric', 'Value', 'Percentage of Total']);
            $k = $analytics['kpis'];
            fputcsv($handle, ['Total Complaints Received', $k['total'], '100%']);
            fputcsv($handle, ['Pending Review', $k['pending'], $k['pending_percent'] . '%']);
            fputcsv($handle, ['Under Investigation / In Progress', $k['in_progress'], $k['in_progress_percent'] . '%']);
            fputcsv($handle, ['Repaired / Resolved Complaints', $k['repaired'], $k['repaired_percent'] . '%']);
            fputcsv($handle, ['Rejected / Spam Complaints', $k['rejected'], $k['rejected_percent'] . '%']);
            fputcsv($handle, ['Resolution Efficiency Rate', $k['efficiency'] . '%', '(Repaired / Total) * 100']);
            fputcsv($handle, ['Average Resolution Time', $analytics['resolution_time_stats']['formatted'], '-']);
            fputcsv($handle, ['SLA Compliance Rate', $analytics['sla_stats']['compliance_rate'] . '%', '-']);
            fputcsv($handle, ['SLA Overdue Complaints', $analytics['sla_stats']['overdue_count'], $analytics['sla_stats']['overdue_percentage'] . '% of active']);
            fputcsv($handle, []);

            // 3. Department-wise Complaint & Resolution Efficiency Matrix
            fputcsv($handle, ['--- SECTION 2: DEPARTMENT-WISE PERFORMANCE & EFFICIENCY ---']);
            fputcsv($handle, [
                'Department Name',
                'Total Cases',
                'Repaired / Resolved',
                'Active (Pending/Progress)',
                'Rejected',
                'Resolution Efficiency (%)',
                'Efficiency Rating',
                'Avg Resolution (Hours)',
                'City Share (%)'
            ]);
            foreach ($analytics['department_stats'] as $d) {
                fputcsv($handle, [
                    $d['department'],
                    $d['total'],
                    $d['repaired'],
                    $d['active'],
                    $d['rejected'],
                    $d['efficiency'] . '%',
                    $d['status_level'],
                    $d['avg_hours'],
                    $d['share_percentage'] . '%'
                ]);
            }
            fputcsv($handle, []);

            // 4. Monthly Complaint Trend
            fputcsv($handle, ['--- SECTION 3: MONTHLY COMPLAINT TRENDS (' . $analytics['monthly_trend']['year'] . ') ---']);
            fputcsv($handle, ['Month', 'Complaints Filed', 'Complaints Repaired', 'Active Cases']);
            foreach ($analytics['monthly_trend']['labels'] as $idx => $mName) {
                fputcsv($handle, [
                    $mName,
                    $analytics['monthly_trend']['filed'][$idx],
                    $analytics['monthly_trend']['repaired'][$idx],
                    $analytics['monthly_trend']['pending'][$idx],
                ]);
            }
            fputcsv($handle, []);

            // 5. Category-wise Complaint Analysis
            fputcsv($handle, ['--- SECTION 4: CATEGORY-WISE COMPLAINT ANALYSIS ---']);
            fputcsv($handle, ['Category', 'Department', 'Total Complaints', 'Repaired', 'Pending', 'Efficiency (%)', 'Severity', 'SLA (Days)']);
            foreach ($analytics['category_stats'] as $cat) {
                fputcsv($handle, [
                    $cat['name'],
                    $cat['department'],
                    $cat['total'],
                    $cat['repaired'],
                    $cat['pending'] + $cat['in_progress'],
                    $cat['efficiency'] . '%',
                    $cat['severity'],
                    $cat['sla_days'],
                ]);
            }
            fputcsv($handle, []);

            // 6. Officer Performance and Workload Statistics
            fputcsv($handle, ['--- SECTION 5: OFFICER PERFORMANCE & WORKLOAD METRICS ---']);
            fputcsv($handle, ['Officer Name', 'Email', 'Assigned Department', 'Total Assigned Cases', 'Repaired Cases', 'Active Cases', 'Efficiency (%)', 'Workload Level']);
            foreach ($analytics['officer_stats'] as $off) {
                fputcsv($handle, [
                    $off['name'],
                    $off['email'],
                    $off['department'],
                    $off['total_assigned'],
                    $off['repaired'],
                    $off['active'],
                    $off['efficiency'] . '%',
                    $off['workload'],
                ]);
            }
            fputcsv($handle, []);

            // 7. SLA Overdue Complaints Summary
            fputcsv($handle, ['--- SECTION 6: SLA BREACHES & OVERDUE COMPLAINTS ---']);
            fputcsv($handle, ['Case ID', 'Category', 'Department', 'Status', 'Reporter', 'Filed Date', 'SLA Deadline', 'Overdue By (Hours)', 'Assigned Officer']);
            foreach ($analytics['sla_stats']['overdue_list'] as $od) {
                fputcsv($handle, [
                    $od['case_number'],
                    $od['category'],
                    $od['department'],
                    $od['status'],
                    $od['reporter_name'],
                    $od['created_at'],
                    $od['deadline'],
                    $od['hours_overdue'] . ' hrs (' . $od['days_overdue'] . ' days)',
                    $od['assigned_officer'],
                ]);
            }
            fputcsv($handle, []);

            // 8. Detailed Report Ledger
            fputcsv($handle, ['--- SECTION 7: DETAILED COMPLAINT REGISTER ---']);
            fputcsv($handle, ['Case ID', 'Category', 'Department', 'Status', 'Location', 'Latitude', 'Longitude', 'Filed At', 'Last Updated']);
            $reportsQuery = Report::query();
            if ($analytics['filters']['department'] !== 'all') {
                $reportsQuery->where('department', $analytics['filters']['department']);
            }
            foreach ($reportsQuery->latest()->get() as $rep) {
                fputcsv($handle, [
                    '#' . str_pad($rep->id, 5, '0', STR_PAD_LEFT),
                    $rep->category,
                    $rep->department,
                    $rep->status,
                    $rep->location,
                    $rep->latitude,
                    $rep->longitude,
                    $rep->created_at ? $rep->created_at->format('Y-m-d H:i:s') : '',
                    $rep->updated_at ? $rep->updated_at->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export Municipal Analytics to Printable Executive PDF document.
     */
    public function exportPdf(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $analytics = $this->analyticsService->getDashboardAnalytics($request);
        $autoPrint = $request->has('print');

        return view('admin.analytics.pdf', compact('analytics', 'autoPrint'));
    }
}
