<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Dynamic Categories Seeder
        $categoriesData = [
            // Health & Sanitation
            ['name' => 'Mosquito Issue', 'department' => User::DEPT_HEALTH, 'sla_days' => 3, 'severity' => 'Medium'],
            ['name' => 'Sanitation Problem', 'department' => User::DEPT_HEALTH, 'sla_days' => 5, 'severity' => 'Medium'],
            ['name' => 'Stray Animal', 'department' => User::DEPT_HEALTH, 'sla_days' => 4, 'severity' => 'Low'],

            // Engineering & Public Works
            ['name' => 'Road Damage', 'department' => User::DEPT_ENGINEERING, 'sla_days' => 7, 'severity' => 'High'],
            ['name' => 'Drainage Block', 'department' => User::DEPT_ENGINEERING, 'sla_days' => 2, 'severity' => 'High'],
            ['name' => 'Broken Footpath', 'department' => User::DEPT_ENGINEERING, 'sla_days' => 6, 'severity' => 'Low'],

            // Solid Waste Collection
            ['name' => 'Garbage Not Collected', 'department' => User::DEPT_WASTE, 'sla_days' => 1, 'severity' => 'Medium'],
            ['name' => 'Overflowing Bin', 'department' => User::DEPT_WASTE, 'sla_days' => 2, 'severity' => 'Medium'],
            ['name' => 'Illegal Dumping', 'department' => User::DEPT_WASTE, 'sla_days' => 3, 'severity' => 'High'],

            // Electrical
            ['name' => 'Street Light Not Working', 'department' => User::DEPT_ELECTRICITY, 'sla_days' => 3, 'severity' => 'Low'],
            ['name' => 'Damaged Electric Pole', 'department' => User::DEPT_ELECTRICITY, 'sla_days' => 1, 'severity' => 'High'],
            ['name' => 'Open Street Wiring', 'department' => User::DEPT_ELECTRICITY, 'sla_days' => 1, 'severity' => 'High'],

            // Water Supply
            ['name' => 'No Water Supply', 'department' => User::DEPT_WATER, 'sla_days' => 1, 'severity' => 'High'],
            ['name' => 'Water Leakage', 'department' => User::DEPT_WATER, 'sla_days' => 2, 'severity' => 'Medium'],
            ['name' => 'Sewer Overflow', 'department' => User::DEPT_WATER, 'sla_days' => 1, 'severity' => 'High'],

            // Planning
            ['name' => 'Illegal Construction', 'department' => User::DEPT_PLANNING, 'sla_days' => 14, 'severity' => 'Medium'],
            ['name' => 'Building Safety Issue', 'department' => User::DEPT_PLANNING, 'sla_days' => 5, 'severity' => 'High'],
            ['name' => 'Road Encroachment', 'department' => User::DEPT_PLANNING, 'sla_days' => 10, 'severity' => 'Medium'],
        ];

        foreach ($categoriesData as $c) {
            Category::updateOrCreate(['name' => $c['name']], $c);
        }

        // Admin
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'),
                'role' => User::ROLE_ADMIN,
            ]
        );

        // Citizen
        User::firstOrCreate(
            ['email' => 'suryakrishnakv@gmail.com'],
            [
                'name' => 'Surya Krishna',
                'password' => Hash::make('surya123'),
                'role' => User::ROLE_CITIZEN,
            ]
        );

        // Officers
        $officers = [
            [
                'name' => 'Health & Sanitation Officer',
                'email' => 'health@dept.com',
                'password' => 'health123',
                'department' => User::DEPT_HEALTH,
            ],
            [
                'name' => 'Engineering & Public Works Officer',
                'email' => 'engineering@dept.com',
                'password' => 'eng123',
                'department' => User::DEPT_ENGINEERING,
            ],
            [
                'name' => 'Solid Waste Management Officer',
                'email' => 'waste@dept.com',
                'password' => 'waste123',
                'department' => User::DEPT_WASTE,
            ],
            [
                'name' => 'Electrical & Street Lighting Officer',
                'email' => 'electrical@dept.com',
                'password' => 'elec123',
                'department' => User::DEPT_ELECTRICITY,
            ],
            [
                'name' => 'Water Supply & Sewerage Officer',
                'email' => 'water@dept.com',
                'password' => 'water123',
                'department' => User::DEPT_WATER,
            ],
            [
                'name' => 'Town Planning Officer',
                'email' => 'planning@dept.com',
                'password' => 'plan123',
                'department' => User::DEPT_PLANNING,
            ],
        ];

        foreach ($officers as $officer) {
            User::firstOrCreate(
                ['email' => $officer['email']],
                [
                    'name' => $officer['name'],
                    'password' => Hash::make($officer['password']),
                    'role' => User::ROLE_OFFICER,
                    'department' => $officer['department'],
                ]
            );
        }

        // Seed municipal wards
        $this->call(WardSeeder::class);

        // Seed realistic GIS complaints
        $this->call(ReportSeeder::class);
    }
}
