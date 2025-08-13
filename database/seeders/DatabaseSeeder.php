<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Request\CreateUser;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run role and permission seeders first
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);

        // Assign all permissions to Admin role
        $adminRole = \App\Models\Role::where('name', 'Admin')->first();
        $allPermissions = \App\Models\Permission::all();

        if ($adminRole && $allPermissions->count() > 0) {
            $adminRole->permissions()->sync($allPermissions->pluck('id'));
        }

        // Create a default admin user if none exists
        if (!\App\Models\User::where('email', 'admin@loadmasta.com')->exists()) {
            $adminUser = \App\Models\User::create([
                'name' => 'System Administrator',
                'email' => 'admin@loadmasta.com',
                'password' => bcrypt('nnnnnnnn'),
                'is_active' => true,
            ]);

            // Assign Admin role to the default user
            if ($adminRole) {
                $adminUser->roles()->attach($adminRole->id);
            }
        }
        $this->createTestUsers();
    }

    private function createTestUsers()
    {
        $roles = Role::all();

        $testUsers = [
            [
                'name' => 'Finance Officer',
                'email' => 'finance@loadmasta.com',
                'password' => 'finance123',
                'roles' => ['Finance']
            ],
            [
                'name' => 'Dispatcher',
                'email' => 'dispatcher@loadmasta.com',
                'password' => 'dispatcher123',
                'roles' => ['Dispatcher']
            ],
            [
                'name' => 'KYC Officer',
                'email' => 'kyc@loadmasta.com',
                'password' => 'kyc123',
                'roles' => ['KYC Officer']
            ],
        ];

        foreach ($testUsers as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                ]
            );

            $roleIds = $roles->whereIn('name', $userData['roles'])->pluck('id');
            $user->roles()->sync($roleIds);
        }
    }
}
