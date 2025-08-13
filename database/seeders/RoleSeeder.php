<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'display_name' => 'Administrator',
                'description' => 'Full system access and control',
                'is_active' => true,
            ],
            [
                'name' => 'Finance',
                'display_name' => 'Finance Officer',
                'description' => 'Access to financial data and transactions',
                'is_active' => true,
            ],
            [
                'name' => 'Dispatcher',
                'display_name' => 'Dispatcher',
                'description' => 'Manage load assignments and driver coordination',
                'is_active' => true,
            ],
            [
                'name' => 'KYC Officer',
                'display_name' => 'KYC Verification Officer',
                'description' => 'Handle KYC document verification',
                'is_active' => true,
            ],
            [
                'name' => 'Support',
                'display_name' => 'Customer Support',
                'description' => 'Handle customer inquiries and support',
                'is_active' => true,
            ],
            [
                'name' => 'Manager',
                'display_name' => 'Operations Manager',
                'description' => 'Oversee operations and team management',
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
} 