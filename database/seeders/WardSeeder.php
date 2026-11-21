<?php

namespace Database\Seeders;

use App\Models\Ward;
use App\Models\User;
use Illuminate\Database\Seeder;

class WardSeeder extends Seeder
{
    public function run(): void
    {
        $wardsData = [
            [
                'ward_number' => 1,
                'name' => 'Kalamassery Municipal Ward',
                'description' => 'Industrial, educational and educational institutions hub including Rajagiri, CUSAT and NH 544 corridor.',
                'center_lat' => 10.0550000,
                'center_lng' => 76.3550000,
                'boundaries' => [
                    [10.0700, 76.3400],
                    [10.0700, 76.3700],
                    [10.0400, 76.3700],
                    [10.0400, 76.3400],
                ],
            ],
            [
                'ward_number' => 2,
                'name' => 'Edappally North Ward',
                'description' => 'High-transit retail and metro corridor comprising Lulu Mall, Edappally Toll and railway bypass junction.',
                'center_lat' => 10.0270000,
                'center_lng' => 76.3120000,
                'boundaries' => [
                    [10.0380, 76.3000],
                    [10.0380, 76.3250],
                    [10.0180, 76.3250],
                    [10.0180, 76.3000],
                ],
            ],
            [
                'ward_number' => 3,
                'name' => 'Kaloor - Palarivattom Ward',
                'description' => 'Urban residential and sports complex area surrounding Jawaharlal Nehru International Stadium and Palarivattom Junction.',
                'center_lat' => 10.0020000,
                'center_lng' => 76.3080000,
                'boundaries' => [
                    [10.0150, 76.2900],
                    [10.0150, 76.3200],
                    [9.9900, 76.3200],
                    [9.9900, 76.2900],
                ],
            ],
            [
                'ward_number' => 4,
                'name' => 'Kakkanad Infopark Zone',
                'description' => 'Technology and municipal civil administration hub including SmartCity, Infopark Phase 1 & 2, and Ernakulam Collectorate.',
                'center_lat' => 10.0120000,
                'center_lng' => 76.3650000,
                'boundaries' => [
                    [10.0250, 76.3500],
                    [10.0250, 76.3800],
                    [10.0000, 76.3800],
                    [10.0000, 76.3500],
                ],
            ],
            [
                'ward_number' => 5,
                'name' => 'Ernakulam Central & Marine Drive',
                'description' => 'Commercial center, waterfront promenade, MG Road commercial strip and High Court zone.',
                'center_lat' => 9.9760000,
                'center_lng' => 76.2770000,
                'boundaries' => [
                    [9.9880, 76.2650],
                    [9.9880, 76.2900],
                    [9.9650, 76.2900],
                    [9.9650, 76.2650],
                ],
            ],
            [
                'ward_number' => 6,
                'name' => 'Aluva Municipal Sector',
                'description' => 'Northern gateway zone covering Periyar riverbank, Market Road, railway hub and metro terminal.',
                'center_lat' => 10.1080000,
                'center_lng' => 76.3520000,
                'boundaries' => [
                    [10.1250, 76.3350],
                    [10.1250, 76.3650],
                    [10.0900, 76.3650],
                    [10.0900, 76.3350],
                ],
            ],
        ];

        // Retrieve existing officers to assign to wards
        $officers = User::where('role', User::ROLE_OFFICER)->get();

        foreach ($wardsData as $data) {
            $ward = Ward::updateOrCreate(
                ['ward_number' => $data['ward_number']],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'center_lat' => $data['center_lat'],
                    'center_lng' => $data['center_lng'],
                    'boundaries' => $data['boundaries'],
                ]
            );

            // Assign at least 2 officers to each ward
            if ($officers->isNotEmpty()) {
                // Distribute officers across wards based on ward number
                $assigned = $officers->filter(function ($officer, $idx) use ($ward, $officers) {
                    return ($idx % 3) === ($ward->ward_number % 3) || ($idx % 2) === ($ward->ward_number % 2);
                });

                if ($assigned->isEmpty()) {
                    $assigned = $officers->take(2);
                }

                $ward->officers()->syncWithoutDetaching($assigned->pluck('id')->toArray());
            }
        }
    }
}
