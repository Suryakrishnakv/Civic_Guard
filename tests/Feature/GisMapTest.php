<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GisMapTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
        $this->seed(\Database\Seeders\ReportSeeder::class);
    }

    public function test_guest_cannot_access_map_data(): void
    {
        $response = $this->getJson(route('reports.mapData'));
        $response->assertStatus(401);
    }

    public function test_citizen_sees_only_authorized_complaints(): void
    {
        $citizen = User::where('role', User::ROLE_CITIZEN)->first();
        $this->assertNotNull($citizen);

        // Create a complaint by another user
        $otherUser = User::factory()->create([
            'role' => User::ROLE_CITIZEN,
            'email' => 'other_citizen@example.com',
        ]);
        Report::create([
            'user_id' => $otherUser->id,
            'type' => 'Damage',
            'category' => 'Road Damage',
            'department' => User::DEPT_ENGINEERING,
            'status' => 'Pending',
            'description' => 'Other citizen secret complaint',
            'location' => 'Somewhere else',
            'latitude' => 10.0538,
            'longitude' => 76.3567,
        ]);

        $response = $this->actingAs($citizen)->get(route('dashboard'));
        $response->assertStatus(200);

        // Citizen calls mapData API
        $mapDataResponse = $this->actingAs($citizen)->getJson(route('reports.mapData'));
        $mapDataResponse->assertStatus(200);

        $reports = $mapDataResponse->json('reports');
        $this->assertNotEmpty($reports);

        // Every report in citizen's map data MUST be owned by the citizen (Requirement 6)
        foreach ($reports as $r) {
            $this->assertTrue($r['is_own']);
            $this->assertNotEquals('Other citizen secret complaint', $r['description']);
        }
    }

    public function test_duplicate_detection_within_200_meters(): void
    {
        $citizen = User::where('role', User::ROLE_CITIZEN)->first();

        // Query near Kalamassery coordinates where reports were seeded (10.0538, 76.3567)
        $response = $this->actingAs($citizen)->getJson(route('reports.checkDuplicate', [
            'latitude' => 10.0540,
            'longitude' => 76.3569,
            'radius' => 200,
        ]));

        $response->assertStatus(200);
        $response->assertJson([
            'has_duplicates' => true,
            'radius_meters' => 200,
        ]);
        $this->assertGreaterThan(0, $response->json('count'));
    }

    public function test_officer_map_data_is_strictly_scoped_to_their_department(): void
    {
        $electricalOfficer = User::where('email', 'electrical@dept.com')->first();
        $this->assertNotNull($electricalOfficer);

        $response = $this->actingAs($electricalOfficer)->getJson(route('reports.mapData'));
        $response->assertStatus(200);

        $reports = $response->json('reports');
        $this->assertNotEmpty($reports);

        // All returned reports must belong to the officer's department (Requirement 6)
        foreach ($reports as $r) {
            $this->assertEquals(User::DEPT_ELECTRICITY, $r['department']);
        }

        // Even if officer attempts to query another department, officer's own department is enforced
        $tamperedResponse = $this->actingAs($electricalOfficer)->getJson(route('reports.mapData', [
            'department' => User::DEPT_HEALTH
        ]));
        $tamperedResponse->assertStatus(200);
        foreach ($tamperedResponse->json('reports') as $r) {
            $this->assertEquals(User::DEPT_ELECTRICITY, $r['department']);
        }
    }

    public function test_admin_has_global_map_access_and_filtering(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();
        $this->assertNotNull($admin);

        // Admin dashboard
        $dashResponse = $this->actingAs($admin)->get(route('admin.dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('admin-dashboard-map');

        // Dedicated Fullscreen Map page
        $mapPageResponse = $this->actingAs($admin)->get(route('admin.reports.map'));
        $mapPageResponse->assertStatus(200);
        $mapPageResponse->assertSee('fullscreen-gis-map');

        // Admin can see all departments (Requirement 6)
        $allData = $this->actingAs($admin)->getJson(route('reports.mapData'));
        $allData->assertStatus(200);
        $this->assertGreaterThan(5, count($allData->json('reports')));

        // Admin can filter by specific department (Requirement 4)
        $engineeringData = $this->actingAs($admin)->getJson(route('reports.mapData', [
            'department' => User::DEPT_ENGINEERING
        ]));
        $engineeringData->assertStatus(200);
        foreach ($engineeringData->json('reports') as $r) {
            $this->assertEquals(User::DEPT_ENGINEERING, $r['department']);
        }

        // Admin can filter by status (Requirement 4)
        $pendingData = $this->actingAs($admin)->getJson(route('reports.mapData', [
            'status' => 'Pending'
        ]));
        $pendingData->assertStatus(200);
        foreach ($pendingData->json('reports') as $r) {
            $this->assertEquals('Pending', $r['status']);
        }

        // Admin can filter by date (Requirement 4)
        $todayData = $this->actingAs($admin)->getJson(route('reports.mapData', [
            'date' => 'today'
        ]));
        $todayData->assertStatus(200);
        $this->assertIsArray($todayData->json('reports'));
    }

    public function test_missing_and_invalid_gps_coordinates_are_safely_ignored(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        // Create reports with missing and invalid coordinates (Requirement 8)
        Report::create([
            'user_id' => $admin->id,
            'type' => 'Damage',
            'category' => 'Road Damage',
            'department' => User::DEPT_ENGINEERING,
            'status' => 'Pending',
            'description' => 'Missing coords',
            'location' => 'No GPS',
            'latitude' => null,
            'longitude' => null,
        ]);

        Report::create([
            'user_id' => $admin->id,
            'type' => 'Damage',
            'category' => 'Road Damage',
            'department' => User::DEPT_ENGINEERING,
            'status' => 'Pending',
            'description' => 'Zero coords',
            'location' => '0,0 coordinates',
            'latitude' => 0.0,
            'longitude' => 0.0,
        ]);

        $response = $this->actingAs($admin)->getJson(route('reports.mapData'));
        $response->assertStatus(200);

        foreach ($response->json('reports') as $r) {
            $this->assertNotNull($r['latitude']);
            $this->assertNotNull($r['longitude']);
            $this->assertNotEquals(0, $r['latitude']);
            $this->assertNotEquals(0, $r['longitude']);
            $this->assertGreaterThanOrEqual(-90, $r['latitude']);
            $this->assertLessThanOrEqual(90, $r['latitude']);
            $this->assertGreaterThanOrEqual(-180, $r['longitude']);
            $this->assertLessThanOrEqual(180, $r['longitude']);
        }
    }

    public function test_popup_data_contains_required_fields(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $response = $this->actingAs($admin)->getJson(route('reports.mapData'));
        $response->assertStatus(200);

        $first = $response->json('reports.0');
        $this->assertNotNull($first);

        // Requirements 3 & 5: Report ID, category, description, address, status, photographs & View Details link
        $this->assertArrayHasKey('id', $first);
        $this->assertArrayHasKey('case_number', $first);
        $this->assertArrayHasKey('category', $first);
        $this->assertArrayHasKey('description', $first);
        $this->assertArrayHasKey('address', $first);
        $this->assertArrayHasKey('status', $first);
        $this->assertArrayHasKey('photos', $first);
        $this->assertArrayHasKey('detail_url', $first);
    }

    public function test_citizen_can_create_report_with_gps_coordinates(): void
    {
        $citizen = User::where('role', User::ROLE_CITIZEN)->first();

        $photo = UploadedFile::fake()->create('test_damage.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($citizen)->post(route('reports.store'), [
            'category' => 'Road Damage',
            'description' => 'Large trench excavated across street without warning signage.',
            'location' => 'Kalamassery Premier Junction',
            'address' => 'Premier Junction, Kalamassery, Ernakulam, Kerala 683104',
            'latitude' => 10.061234,
            'longitude' => 76.348912,
            'photos' => [$photo],
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('reports', [
            'category' => 'Road Damage',
            'department' => User::DEPT_ENGINEERING,
            'location' => 'Kalamassery Premier Junction',
            'latitude' => 10.061234,
            'longitude' => 76.348912,
        ]);
    }
}
