<?php

namespace Database\Seeders;

use App\Models\User;
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
        // Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => User::ROLE_ADMIN,
        ]);

        // Citizen
        User::create([
            'name' => 'Surya Krishna',
            'email' => 'suryakrishnakv@gmail.com',
            'password' => Hash::make('surya123'),
            'role' => User::ROLE_CITIZEN,
        ]);

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
            User::create([
                'name' => $officer['name'],
                'email' => $officer['email'],
                'password' => Hash::make($officer['password']),
                'role' => User::ROLE_OFFICER,
                'department' => $officer['department'],
            ]);
        }
    }
}
