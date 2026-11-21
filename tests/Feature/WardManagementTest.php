<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ward;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WardManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $officer;
    protected User $citizen;
    protected Ward $sampleWard;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $this->officer = User::factory()->create([
            'role' => User::ROLE_OFFICER,
            'department' => User::DEPT_ENGINEERING,
        ]);

        $this->citizen = User::factory()->create([
            'role' => User::ROLE_CITIZEN,
        ]);

        // Create a standard test ward with known square polygon
        // Lat: 10.0400 to 10.0600, Lng: 76.3400 to 76.3600
        $this->sampleWard = Ward::create([
            'ward_number' => 101,
            'name' => 'Test Central Ward',
            'description' => 'Test ward for point-in-polygon verification',
            'center_lat' => 10.0500,
            'center_lng' => 76.3500,
            'boundaries' => [
                [10.0400, 76.3400],
                [10.0400, 76.3600],
                [10.0600, 76.3600],
                [10.0600, 76.3400],
            ],
        ]);

        $this->sampleWard->officers()->attach($this->officer->id);
    }

    public function test_point_in_polygon_detection_works_accurately(): void
    {
        // Coordinate strictly inside the square
        $this->assertTrue($this->sampleWard->containsPoint(10.0500, 76.3500));

        // Coordinate outside the square
        $this->assertFalse($this->sampleWard->containsPoint(10.0700, 76.3500));
        $this->assertFalse($this->sampleWard->containsPoint(10.0500, 76.3700));

        // Static finder returns correct model
        $found = Ward::findWardByCoordinates(10.0500, 76.3500);
        $this->assertNotNull($found);
        $this->assertEquals($this->sampleWard->id, $found->id);

        // Outside returns null
        $outside = Ward::findWardByCoordinates(10.1000, 76.4000);
        $this->assertNull($outside);
    }

    public function test_citizen_detect_ward_endpoint(): void
    {
        // Inside sample ward
        $response = $this->actingAs($this->citizen)->getJson('/reports/detect-ward?latitude=10.0500&longitude=76.3500');

        $response->assertOk()
            ->assertJson([
                'found' => true,
                'ward' => [
                    'id' => $this->sampleWard->id,
                    'ward_number' => 101,
                    'name' => 'Test Central Ward',
                ]
            ]);

        // Outside any ward
        $outsideResponse = $this->actingAs($this->citizen)->getJson('/reports/detect-ward?latitude=10.9999&longitude=76.9999');
        $outsideResponse->assertOk()
            ->assertJson([
                'found' => false,
            ]);
    }

    public function test_citizen_report_submission_auto_assigns_ward(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        $photo = \Illuminate\Http\UploadedFile::fake()->create('damage.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->citizen)->post('/reports', [
            'category' => 'Road Damage',
            'description' => 'Pothole inside ward boundary',
            'location' => 'Central Plaza, Ward 101',
            'latitude' => 10.0500,
            'longitude' => 76.3500,
            'photos' => [$photo],
        ]);

        $response->assertRedirect('/dashboard');

        $report = Report::latest()->first();
        $this->assertNotNull($report);
        $this->assertEquals($this->sampleWard->id, $report->ward_id);
    }

    public function test_admin_can_view_wards_and_create_ward(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.wards.index'));
        $response->assertOk()
            ->assertSee('Municipal Ward Management')
            ->assertSee('Test Central Ward');

        // View create form
        $createView = $this->actingAs($this->admin)->get(route('admin.wards.create'));
        $createView->assertOk()
            ->assertSee('Define Municipal Ward');

        // Store new ward
        $storeResponse = $this->actingAs($this->admin)->post(route('admin.wards.store'), [
            'ward_number' => 102,
            'name' => 'Kalamassery North Ward',
            'description' => 'Industrial zone north of highway',
            'boundaries' => json_encode([
                [10.0600, 76.3400],
                [10.0600, 76.3700],
                [10.0800, 76.3700],
                [10.0800, 76.3400],
            ]),
            'officers' => [$this->officer->id],
        ]);

        $storeResponse->assertRedirect(route('admin.wards.index'));

        $newWard = Ward::where('ward_number', 102)->first();
        $this->assertNotNull($newWard);
        $this->assertEquals('Kalamassery North Ward', $newWard->name);
        $this->assertEquals(10.07, round($newWard->center_lat, 2));
        $this->assertEquals(76.355, round($newWard->center_lng, 3));
        $this->assertTrue($newWard->officers->contains($this->officer->id));
    }

    public function test_admin_can_update_and_delete_ward(): void
    {
        // Update
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.wards.update', $this->sampleWard->id), [
            'ward_number' => 101,
            'name' => 'Updated Central Ward',
            'description' => 'Updated zone description',
            'boundaries' => json_encode([
                [10.0350, 76.3350],
                [10.0350, 76.3650],
                [10.0650, 76.3650],
                [10.0650, 76.3350],
            ]),
            'officers' => [$this->officer->id],
        ]);

        $updateResponse->assertRedirect(route('admin.wards.index'));
        $this->sampleWard->refresh();
        $this->assertEquals('Updated Central Ward', $this->sampleWard->name);

        // Delete
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.wards.destroy', $this->sampleWard->id));
        $deleteResponse->assertRedirect(route('admin.wards.index'));
        $this->assertDatabaseMissing('wards', ['id' => $this->sampleWard->id]);
    }

    public function test_officer_can_view_ward_details_and_inspection_report(): void
    {
        // Officer viewing ward show page
        $showResponse = $this->actingAs($this->officer)->get(route('admin.wards.show', $this->sampleWard->id));
        $showResponse->assertOk()
            ->assertSee($this->sampleWard->name)
            ->assertSee('Ward Complaints Register');

        // Officer generating inspection report
        $inspectionResponse = $this->actingAs($this->officer)->get(route('admin.wards.inspectionReport', $this->sampleWard->id));
        $inspectionResponse->assertOk()
            ->assertSee('MUNICIPAL FIELD WORK & INSPECTION REGISTER', false)
            ->assertSee($this->sampleWard->name);
    }

    public function test_officer_cannot_create_or_delete_wards(): void
    {
        $createResponse = $this->actingAs($this->officer)->get(route('admin.wards.create'));
        $createResponse->assertStatus(403);

        $storeResponse = $this->actingAs($this->officer)->post(route('admin.wards.store'), [
            'ward_number' => 999,
            'name' => 'Unauthorized Ward',
            'boundaries' => json_encode([[1, 1], [1, 2], [2, 2]]),
        ]);
        $storeResponse->assertStatus(403);

        $deleteResponse = $this->actingAs($this->officer)->delete(route('admin.wards.destroy', $this->sampleWard->id));
        $deleteResponse->assertStatus(403);
    }

    public function test_citizen_cannot_access_ward_admin_routes(): void
    {
        $indexResponse = $this->actingAs($this->citizen)->get(route('admin.wards.index'));
        $indexResponse->assertStatus(403);

        $showResponse = $this->actingAs($this->citizen)->get(route('admin.wards.show', $this->sampleWard->id));
        $showResponse->assertStatus(403);
    }

    public function test_export_ward_analytics_to_excel_and_pdf(): void
    {
        // Excel CSV export
        $excelResponse = $this->actingAs($this->admin)->get(route('admin.wards.export.excel'));
        $excelResponse->assertOk();
        $this->assertStringContainsString('text/csv', $excelResponse->headers->get('Content-Type'));

        // PDF print view
        $pdfResponse = $this->actingAs($this->admin)->get(route('admin.wards.export.pdf'));
        $pdfResponse->assertOk()
            ->assertSee('CIVICGUARD MUNICIPAL CORPORATION')
            ->assertSee('Ward Performance & Officer Allocation Register', false);
    }
}
