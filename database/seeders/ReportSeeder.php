<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $citizen = User::where('role', User::ROLE_CITIZEN)->first();
        if (!$citizen) {
            $citizen = User::factory()->create([
                'name' => 'Surya Krishna',
                'email' => 'suryakrishnakv@gmail.com',
                'role' => User::ROLE_CITIZEN,
                'password' => bcrypt('surya123'),
            ]);
        }

        $officers = User::where('role', User::ROLE_OFFICER)->get()->keyBy('department');

        $reportsData = [
            // Kalamassery Cluster (High Density & 200m proximity)
            [
                'category' => 'Road Damage',
                'department' => User::DEPT_ENGINEERING,
                'status' => 'Pending',
                'description' => 'Deep pothole on the main approach road right near Rajagiri College gate causing traffic bottleneck and accident risk for two-wheelers.',
                'location' => 'Near RCSS Campus, Kalamassery',
                'address' => 'Rajagiri Road, Kalamassery, Kochi, Kerala 683104',
                'latitude' => 10.05380,
                'longitude' => 76.35670,
                'created_at' => now()->subHours(3),
            ],
            [
                'category' => 'Broken Footpath',
                'department' => User::DEPT_ENGINEERING,
                'status' => 'In Progress',
                'description' => 'Broken pedestrian walkway slabs and missing inspection cover directly opposite the college bus stop.',
                'location' => 'Opposite Rajagiri Valley, Kalamassery',
                'address' => 'Kalamassery Municipal Road, Kochi, Kerala 683104',
                'latitude' => 10.05440,
                'longitude' => 76.35720, // ~85m from #1
                'remarks' => 'Inspection done by field team. Work order issued to paving contractor.',
                'created_at' => now()->subDays(1),
            ],
            [
                'category' => 'Street Light Not Working',
                'department' => User::DEPT_ELECTRICITY,
                'status' => 'Pending',
                'description' => 'Two consecutive LED street lighting poles flickering and turning off after sunset, pitch dark at night.',
                'location' => 'Campus North Junction, Kalamassery',
                'address' => 'NH 544 Service Road, Kalamassery, Kochi 683104',
                'latitude' => 10.05320,
                'longitude' => 76.35610, // ~90m from #1
                'created_at' => now()->subHours(6),
            ],

            // Edappally Cluster
            [
                'category' => 'Garbage Not Collected',
                'department' => User::DEPT_WASTE,
                'status' => 'In Progress',
                'description' => 'Commercial waste pile accumulated near toll booth for 3 consecutive days, foul odor spreading to nearby shops.',
                'location' => 'Edappally Toll Junction',
                'address' => 'Edappally Toll, Edappally, Kochi, Kerala 682024',
                'latitude' => 10.02610,
                'longitude' => 76.31250,
                'remarks' => 'Compactor truck dispatched for immediate clearance.',
                'created_at' => now()->subDays(2),
            ],
            [
                'category' => 'Overflowing Bin',
                'department' => User::DEPT_WASTE,
                'status' => 'Pending',
                'description' => 'Public community dustbin overflowing onto the pavement causing pedestrian blockage.',
                'location' => 'Near Edappally Metro Station Exit 2',
                'address' => 'Metro Pillar 380, Edappally, Kochi, Kerala 682024',
                'latitude' => 10.02680,
                'longitude' => 76.31310, // ~100m from #4
                'created_at' => now()->subHours(12),
            ],
            [
                'category' => 'Water Leakage',
                'department' => User::DEPT_WATER,
                'status' => 'Pending',
                'description' => 'Underground main water pipeline cracked with clean drinking water gushing onto the road since morning.',
                'location' => 'Lulu Mall Service Road, Edappally',
                'address' => 'Old NH 47, Edappally, Kochi, Kerala 682024',
                'latitude' => 10.02840,
                'longitude' => 76.30820,
                'created_at' => now()->subDays(1),
            ],

            // Palarivattom & Kaloor
            [
                'category' => 'Sewer Overflow',
                'department' => User::DEPT_WATER,
                'status' => 'In Progress',
                'description' => 'Drainage inspection chamber choked and backflowing sewage onto the service road during rush hour.',
                'location' => 'Palarivattom Bypass Junction',
                'address' => 'Palarivattom, Kochi, Kerala 682025',
                'latitude' => 10.00550,
                'longitude' => 76.31170,
                'remarks' => 'Suction jetting machine engaged on site.',
                'created_at' => now()->subDays(3),
            ],
            [
                'category' => 'Mosquito Issue',
                'department' => User::DEPT_HEALTH,
                'status' => 'Pending',
                'description' => 'Stagnant water logging along the ditch creating heavy mosquito breeding grounds for surrounding residences.',
                'location' => 'Near Jawaharlal Nehru International Stadium',
                'address' => 'Stadium Link Road, Kaloor, Kochi, Kerala 682017',
                'latitude' => 9.99840,
                'longitude' => 76.29990,
                'created_at' => now()->subDays(2),
            ],

            // Kakkanad Infopark Zone
            [
                'category' => 'Damaged Electric Pole',
                'department' => User::DEPT_ELECTRICITY,
                'status' => 'Repaired',
                'description' => 'Concrete pole titled at dangerous angle after delivery truck collision.',
                'location' => 'Infopark Expressway, Kakkanad',
                'address' => 'Infopark Phase 1, Kakkanad, Kochi, Kerala 682042',
                'latitude' => 10.01040,
                'longitude' => 76.36390,
                'remarks' => 'Damaged pole replaced with fresh HT concrete pole and cables re-tensioned safely.',
                'created_at' => now()->subDays(5),
            ],
            [
                'category' => 'Open Street Wiring',
                'department' => User::DEPT_ELECTRICITY,
                'status' => 'Pending',
                'description' => 'Junction box cover hanging loose with exposed 230V live cables within reach of children.',
                'location' => 'Civil Station Road, Kakkanad',
                'address' => 'Kakkanad Civil Station, Kochi, Kerala 682030',
                'latitude' => 10.01250,
                'longitude' => 76.36550,
                'created_at' => now()->subHours(4),
            ],

            // Ernakulam City & Marine Drive
            [
                'category' => 'Sanitation Problem',
                'department' => User::DEPT_HEALTH,
                'status' => 'Repaired',
                'description' => 'Public comfort station pipeline damaged and waste spreading on walkway.',
                'location' => 'MG Road, Shenoys Junction',
                'address' => 'Mahatma Gandhi Rd, Shenoys, Kochi, Kerala 682035',
                'latitude' => 9.97230,
                'longitude' => 76.27830,
                'remarks' => 'Sanitation crew cleared and chlorinated the area. Plumbing fully renewed.',
                'created_at' => now()->subDays(6),
            ],
            [
                'category' => 'Stray Animal',
                'department' => User::DEPT_HEALTH,
                'status' => 'Rejected',
                'description' => 'Aggressive stray dog pack near commercial complex.',
                'location' => 'Marine Drive Walkway, Ernakulam',
                'address' => 'Marine Drive, Ernakulam, Kochi, Kerala 682031',
                'latitude' => 9.97980,
                'longitude' => 76.27580,
                'remarks' => 'Animal Birth Control team inspected; area clear at time of inspection.',
                'created_at' => now()->subDays(4),
            ],
            [
                'category' => 'Drainage Block',
                'department' => User::DEPT_ENGINEERING,
                'status' => 'Repaired',
                'description' => 'Stormwater culvert filled with silt causing monsoon flood on road.',
                'location' => 'Aluva Market Road Culvert',
                'address' => 'Market Road, Aluva, Kochi, Kerala 683101',
                'latitude' => 10.10760,
                'longitude' => 76.35160,
                'remarks' => 'Excavator completed desilting. Water flowing freely.',
                'created_at' => now()->subDays(7),
            ],
            [
                'category' => 'Illegal Construction',
                'department' => User::DEPT_PLANNING,
                'status' => 'Pending',
                'description' => 'Encroachment of public road margin by unauthorized temporary commercial shed.',
                'location' => 'Premier Junction, North Kalamassery',
                'address' => 'Premier Junction, Kalamassery, Kochi 683104',
                'latitude' => 10.06120,
                'longitude' => 76.34890,
                'created_at' => now()->subDays(1),
            ],
        ];

        foreach ($reportsData as $data) {
            $officer = $officers[$data['department']] ?? null;
            $ward = Ward::findWardByCoordinates($data['latitude'], $data['longitude']);

            Report::create([
                'user_id' => $citizen->id,
                'type' => 'Damage',
                'category' => $data['category'],
                'department' => $data['department'],
                'status' => $data['status'],
                'description' => $data['description'],
                'location' => $data['location'],
                'address' => $data['address'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'ward_id' => $ward ? $ward->id : null,
                'assigned_officer_id' => $officer ? $officer->id : null,
                'remarks' => $data['remarks'] ?? null,
                'created_at' => $data['created_at'],
                'updated_at' => $data['created_at'],
            ]);
        }
    }
}
