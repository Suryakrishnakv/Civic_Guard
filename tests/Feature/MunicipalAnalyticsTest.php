<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Report;
use App\Models\Category;
use App\Models\ReportStatusHistory;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MunicipalAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed users, categories, departments, and realistic Kochi GIS complaints
        $this->seed(DatabaseSeeder::class);
    }

    public function test_admin_can_access_advanced_analytics_dashboard(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();
        $this->assertNotNull($admin);

        // 1. Admin accesses main dashboard which embeds the Analytics Dashboard
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Advanced Municipal Analytics Dashboard');
        $response->assertSee('Resolution Efficiency');
        $response->assertSee('Department Caseload');

        // 2. Admin accesses dedicated analytics page
        $analyticsResponse = $this->actingAs($admin)->get(route('admin.analytics.index'));
        $analyticsResponse->assertStatus(200);
        $analyticsResponse->assertSee('Municipal Intelligence');
        $analyticsResponse->assertSee('Category-Wise Complaint Analysis');
        $analyticsResponse->assertSee('Officer Workload & Resolution Efficiency', false);
    }

    public function test_non_admin_cannot_access_analytics_routes(): void
    {
        $citizen = User::where('role', User::ROLE_CITIZEN)->first();
        $officer = User::where('role', User::ROLE_OFFICER)->first();

        // Guest is redirected
        $this->get(route('admin.analytics.index'))->assertRedirect(route('login.form'));
        $this->get(route('admin.analytics.export.excel'))->assertRedirect(route('login.form'));
        $this->get(route('admin.analytics.export.pdf'))->assertRedirect(route('login.form'));

        // Citizen is forbidden (403)
        $this->actingAs($citizen)->get(route('admin.analytics.index'))->assertStatus(403);
        $this->actingAs($citizen)->get(route('admin.analytics.export.excel'))->assertStatus(403);
        $this->actingAs($citizen)->get(route('admin.analytics.export.pdf'))->assertStatus(403);

        // Officer is forbidden (403)
        $this->actingAs($officer)->get(route('admin.analytics.index'))->assertStatus(403);
        $this->actingAs($officer)->get(route('admin.analytics.export.excel'))->assertStatus(403);
        $this->actingAs($officer)->get(route('admin.analytics.export.pdf'))->assertStatus(403);
    }

    public function test_kpi_cards_and_resolution_efficiency_calculation(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $response = $this->actingAs($admin)->getJson(route('admin.analytics.data'));
        $response->assertStatus(200);

        $kpis = $response->json('analytics.kpis');
        $this->assertNotNull($kpis);

        // Check required KPI metrics
        $this->assertArrayHasKey('total', $kpis);
        $this->assertArrayHasKey('pending', $kpis);
        $this->assertArrayHasKey('in_progress', $kpis);
        $this->assertArrayHasKey('repaired', $kpis);
        $this->assertArrayHasKey('rejected', $kpis);
        $this->assertArrayHasKey('efficiency', $kpis);

        // Verify mathematical formula: Efficiency = (Repaired / Total) * 100
        $total = $kpis['total'];
        $repaired = $kpis['repaired'];
        $expectedEfficiency = $total > 0 ? round(($repaired / $total) * 100, 1) : 0.0;

        $this->assertEquals($expectedEfficiency, $kpis['efficiency']);
        $this->assertGreaterThan(0, $total);
    }

    public function test_department_wise_distribution_and_efficiency(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $response = $this->actingAs($admin)->getJson(route('admin.analytics.data'));
        $response->assertStatus(200);

        $deptStats = $response->json('analytics.department_stats');
        $this->assertNotEmpty($deptStats);
        $this->assertCount(6, $deptStats); // All 6 municipal departments

        foreach ($deptStats as $dept) {
            $this->assertArrayHasKey('department', $dept);
            $this->assertArrayHasKey('total', $dept);
            $this->assertArrayHasKey('repaired', $dept);
            $this->assertArrayHasKey('efficiency', $dept);
            $this->assertArrayHasKey('status_level', $dept);

            // Verify efficiency formula for each department
            $expectedDeptEfficiency = $dept['total'] > 0 
                ? round(($dept['repaired'] / $dept['total']) * 100, 1) 
                : 0.0;
            $this->assertEquals($expectedDeptEfficiency, $dept['efficiency']);
        }
    }

    public function test_monthly_and_yearly_trends_with_date_filters(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        // 1. Check default monthly trends
        $response = $this->actingAs($admin)->getJson(route('admin.analytics.data'));
        $response->assertStatus(200);

        $monthlyTrend = $response->json('analytics.monthly_trend');
        $this->assertNotNull($monthlyTrend);
        $this->assertCount(12, $monthlyTrend['labels']);
        $this->assertCount(12, $monthlyTrend['filed']);
        $this->assertCount(12, $monthlyTrend['repaired']);

        // 2. Query with specific Year filter
        $yearResponse = $this->actingAs($admin)->getJson(route('admin.analytics.data', ['year' => now()->year]));
        $yearResponse->assertStatus(200);
        $this->assertEquals(now()->year, $yearResponse->json('analytics.monthly_trend.year'));

        // 3. Query with quick range filter
        $rangeResponse = $this->actingAs($admin)->getJson(route('admin.analytics.data', ['range' => 'last_30_days']));
        $rangeResponse->assertStatus(200);
        $this->assertEquals('last_30_days', $rangeResponse->json('analytics.filters.range'));
    }

    public function test_average_complaint_resolution_time_calculation(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $response = $this->actingAs($admin)->getJson(route('admin.analytics.data'));
        $response->assertStatus(200);

        $resTimes = $response->json('analytics.resolution_time_stats');
        $this->assertNotNull($resTimes);
        $this->assertArrayHasKey('avg_hours', $resTimes);
        $this->assertArrayHasKey('avg_days', $resTimes);
        $this->assertArrayHasKey('formatted', $resTimes);
        $this->assertGreaterThan(0, $resTimes['count_resolved']);
    }

    public function test_category_wise_complaint_analysis(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $response = $this->actingAs($admin)->getJson(route('admin.analytics.data'));
        $response->assertStatus(200);

        $categoryStats = $response->json('analytics.category_stats');
        $this->assertNotEmpty($categoryStats);

        $first = $categoryStats[0];
        $this->assertArrayHasKey('name', $first);
        $this->assertArrayHasKey('department', $first);
        $this->assertArrayHasKey('total', $first);
        $this->assertArrayHasKey('repaired', $first);
        $this->assertArrayHasKey('efficiency', $first);
        $this->assertArrayHasKey('severity', $first);
        $this->assertArrayHasKey('sla_days', $first);
    }

    public function test_officer_performance_and_workload_statistics(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $response = $this->actingAs($admin)->getJson(route('admin.analytics.data'));
        $response->assertStatus(200);

        $officerStats = $response->json('analytics.officer_stats');
        $this->assertNotEmpty($officerStats);
        $this->assertCount(6, $officerStats);

        foreach ($officerStats as $officer) {
            $this->assertArrayHasKey('name', $officer);
            $this->assertArrayHasKey('email', $officer);
            $this->assertArrayHasKey('department', $officer);
            $this->assertArrayHasKey('total_assigned', $officer);
            $this->assertArrayHasKey('repaired', $officer);
            $this->assertArrayHasKey('efficiency', $officer);
            $this->assertArrayHasKey('workload', $officer);
        }
    }

    public function test_sla_overdue_complaint_summary(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $response = $this->actingAs($admin)->getJson(route('admin.analytics.data'));
        $response->assertStatus(200);

        $slaStats = $response->json('analytics.sla_stats');
        $this->assertNotNull($slaStats);
        $this->assertArrayHasKey('total_active', $slaStats);
        $this->assertArrayHasKey('overdue_count', $slaStats);
        $this->assertArrayHasKey('compliance_rate', $slaStats);
        $this->assertArrayHasKey('overdue_list', $slaStats);
    }

    public function test_recent_complaint_activities_log(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $response = $this->actingAs($admin)->getJson(route('admin.analytics.data'));
        $response->assertStatus(200);

        $activities = $response->json('analytics.recent_activities');
        $this->assertNotEmpty($activities);

        $first = $activities[0];
        $this->assertArrayHasKey('case_number', $first);
        $this->assertArrayHasKey('action', $first);
        $this->assertArrayHasKey('status', $first);
        $this->assertArrayHasKey('time', $first);
    }

    public function test_export_analytics_to_excel(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $response = $this->actingAs($admin)->get(route('admin.analytics.export.excel'));
        $response->assertStatus(200);

        $this->assertTrue(str_contains($response->headers->get('content-type'), 'text/csv'));
        $this->assertTrue(str_contains($response->headers->get('content-disposition'), 'attachment; filename="CivicGuard_Municipal_Analytics_'));

        // Stream and read content
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertStringContainsString('CIVICGUARD MUNICIPAL ANALYTICS REPORT', $content);
        $this->assertStringContainsString('SECTION 1: KEY PERFORMANCE INDICATORS', $content);
        $this->assertStringContainsString('SECTION 2: DEPARTMENT-WISE PERFORMANCE', $content);
        $this->assertStringContainsString('SECTION 5: OFFICER PERFORMANCE', $content);
        $this->assertStringContainsString('Resolution Efficiency Rate', $content);
    }

    public function test_export_analytics_to_pdf_report(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $response = $this->actingAs($admin)->get(route('admin.analytics.export.pdf'));
        $response->assertStatus(200);
        $response->assertSee('CivicGuard Municipal Corporation');
        $response->assertSee('Executive Municipal Asset & Complaint Analytics Report', false);
        $response->assertSee('Department-Wise Complaint Distribution & Resolution Efficiency', false);
    }
}
