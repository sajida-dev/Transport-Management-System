<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            // Driver management
            [
                'name' => 'driver.create',
                'display_name' => 'Create Driver',
                'description' => 'Allows the user to create driver profiles.'
            ],
            [
                'name' => 'driver.edit',
                'display_name' => 'Edit Driver',
                'description' => 'Allows the user to edit driver profiles.'
            ],
            [
                'name' => 'driver.delete',
                'display_name' => 'Delete Driver',
                'description' => 'Allows the user to delete driver profiles.'
            ],
            [
                'name' => 'driver.view',
                'display_name' => 'View Drivers',
                'description' => 'Allows the user to view driver list and details.'
            ],

            // Driver KYC
            [
                'name' => 'driver.kyc.upload',
                'display_name' => 'Upload Driver KYC',
                'description' => 'Allows the user to upload driver KYC documents.'
            ],
            [
                'name' => 'driver.kyc.view',
                'display_name' => 'View Driver KYC',
                'description' => 'Allows the user to view driver KYC documents.'
            ],
            [
                'name' => 'driver.kyc.delete',
                'display_name' => 'Delete Driver KYC',
                'description' => 'Allows the user to delete driver KYC documents.'
            ],

            // Driver assignments
            [
                'name' => 'driver.assign',
                'display_name' => 'Assign Driver to Truck',
                'description' => 'Allows the user to assign drivers to trucks.'
            ],
            [
                'name' => 'driver.unassign',
                'display_name' => 'Unassign Driver from Truck',
                'description' => 'Allows the user to unassign drivers from trucks.'
            ],

            // Logs and trips
            [
                'name' => 'driver.logs.view',
                'display_name' => 'View Driver Logs',
                'description' => 'Allows the user to view driver logs.'
            ],
            [
                'name' => 'driver.trips.active',
                'display_name' => 'View Active Trips',
                'description' => 'Allows the user to view drivers’ active trips.'
            ],
            [
                'name' => 'driver.trips.history',
                'display_name' => 'View Trip History',
                'description' => 'Allows the user to view trip history of drivers.'
            ],


            // Transporter Management
            [
                'name' => 'transporter.view',
                'display_name' => 'View Transporters',
                'description' => 'Allows the user to view all registered transporters.'
            ],
            [
                'name' => 'transporter.create',
                'display_name' => 'Add Transporter',
                'description' => 'Allows the user to add a new transporter company.'
            ],
            [
                'name' => 'transporter.edit',
                'display_name' => 'Edit Transporter',
                'description' => 'Allows the user to update transporter company profiles.'
            ],
            [
                'name' => 'transporter.show',
                'display_name' => 'View Transporter',
                'description' => 'Allows the user to view transporter company details.'
            ],
            [
                'name' => 'transporter.delete',
                'display_name' => 'Delete Transporter',
                'description' => 'Allows the user to delete transporter companies.'
            ],
            [
                'name' => 'transporter.trucks.manage',
                'display_name' => 'Manage Transporter Trucks',
                'description' => 'Allows the user to link or manage trucks for transporters.'
            ],
            [
                'name' => 'transporter.trucks.create',
                'display_name' => 'Create Transporter Truck',
                'description' => 'Allows the user to create new trucks for transporters.'
            ],
            [
                'name' => 'transporter.trucks.edit',
                'display_name' => 'Edit Transporter Truck',
                'description' => 'Allows the user to edit transporter truck details.'
            ],
            [
                'name' => 'transporter.trucks.delete',
                'display_name' => 'Delete Transporter Truck',
                'description' => 'Allows the user to delete transporter trucks.'
            ],
            [
                'name' => 'transporter.trucks.show',
                'display_name' => 'View Transporter Trucks',
                'description' => 'Allows the user to view all trucks associated with transporters.'
            ],
            [
                'name' => 'transporter.suspend',
                'display_name' => 'Suspend Transporter',
                'description' => 'Allows the user to suspend transporter accounts.'
            ],
            [
                'name' => 'transporter.reactivate',
                'display_name' => 'Reactivate Transporter',
                'description' => 'Allows the user to reactivate suspended transporter accounts.'
            ],

            // Load Management
            [
                'name' => 'load.view',
                'display_name' => 'View Loads',
                'description' => 'Allows the user to view all submitted or manually added loads.'
            ],
            [
                'name' => 'load.create',
                'display_name' => 'Create Load',
                'description' => 'Allows the user to create new loads manually.'
            ],
            [
                'name' => 'load.edit',
                'display_name' => 'Edit Load',
                'description' => 'Allows the user to edit load details.'
            ],
            [
                'name' => 'load.reassign',
                'display_name' => 'Reassign Load',
                'description' => 'Allows the user to reassign loads to different drivers or trucks.'
            ],
            [
                'name' => 'load.cancel',
                'display_name' => 'Cancel Load',
                'description' => 'Allows the user to cancel a load.'
            ],
            [
                'name' => 'load.delete',
                'display_name' => 'Delete Load',
                'description' => 'Allows the user to permanently delete loads.'
            ],
            [
                'name' => 'load.filter.status',
                'display_name' => 'Filter Loads by Status',
                'description' => 'Allows the user to filter loads by status like Pending, In Transit, etc.'
            ],
            // Booking Management
            [
                'name' => 'booking.view',
                'display_name' => 'View Bookings',
                'description' => 'Allows the user to monitor all load-booking activity.'
            ],
            [
                'name' => 'booking.assign',
                'display_name' => 'Assign Drivers and Trucks to Bookings',
                'description' => 'Allows the user to assign drivers and trucks to bookings.'
            ],
            [
                'name' => 'booking.create',
                'display_name' => 'Create Bookings',
                'description' => 'Allows the user to create new bookings.'
            ],
            [
                'name' => 'booking.edit',
                'display_name' => 'Edit Bookings',
                'description' => 'Allows the user to edit booking details and statuses.'
            ],
            [
                'name' => 'booking.cancel',
                'display_name' => 'Cancel Bookings',
                'description' => 'Allows the user to cancel bookings.'
            ],
            [
                'name' => 'booking.history.view',
                'display_name' => 'View Booking History',
                'description' => 'Allows the user to view booking history and timeline.'
            ],
            // Invoice Management
            [
                'name' => 'invoice.view',
                'display_name' => 'View Invoices',
                'description' => 'Allows the user to view invoice list and details.'
            ],
            [
                'name' => 'invoice.generate',
                'display_name' => 'Generate Invoices',
                'description' => 'Allows the user to auto-generate invoices for completed bookings.'
            ],
            [
                'name' => 'invoice.status.track',
                'display_name' => 'Track Invoice Status',
                'description' => 'Allows the user to track the status of invoices (Paid, Unpaid, Overdue).'
            ],
            [
                'name' => 'invoice.download',
                'display_name' => 'Download Invoice Reports',
                'description' => 'Allows the user to download invoice reports in PDF or Excel.'
            ],
            [
                'name' => 'invoice.payment.record',
                'display_name' => 'Manage Payment Records',
                'description' => 'Allows the user to view or edit payment entries and ledger information.'
            ],
            // Real-Time Operational Metrics
            [
                'name' => 'metrics.view.transit_trucks',
                'display_name' => 'View Trucks In Transit',
                'description' => 'Allows the user to view real-time count of trucks currently on active jobs.'
            ],
            [
                'name' => 'metrics.view.pending_loads',
                'display_name' => 'View Pending Loads',
                'description' => 'Allows the user to see loads awaiting pickup or assignment.'
            ],
            [
                'name' => 'metrics.view.delivered_loads',
                'display_name' => 'View Delivered Loads',
                'description' => 'Allows the user to see historical data of successfully delivered loads.'
            ],
            // KYC Management
            [
                'name' => 'kyc.view',
                'display_name' => 'View KYC Submissions',
                'description' => 'Allows the user to view all submitted KYC documents.'
            ],
            [
                'name' => 'kyc.verify',
                'display_name' => 'Verify KYC',
                'description' => 'Allows the user to accept, reject, or flag KYC documents.'
            ],
            [
                'name' => 'kyc.notify',
                'display_name' => 'Send KYC Notifications',
                'description' => 'Allows the user to notify users with incomplete or invalid KYC submissions.'
            ],
            [
                'name' => 'kyc.view.driver',
                'display_name' => 'View Driver KYC',
                'description' => 'Allows the user to view and manage driver KYC documents.'
            ],
            [
                'name' => 'kyc.view.transporter',
                'display_name' => 'View Transporter KYC',
                'description' => 'Allows the user to view and manage transporter KYC documents.'
            ],
            [
                'name' => 'kyc.view.load_owner',
                'display_name' => 'View Load Owner KYC',
                'description' => 'Allows the user to view and manage load owner KYC documents.'
            ],
            // User Management (Admin Panel)
            [
                'name' => 'user.create',
                'display_name' => 'Create Users',
                'description' => 'Allows the user to add new back office users.'
            ],
            [
                'name' => 'user.edit',
                'display_name' => 'Edit Users',
                'description' => 'Allows the user to edit back office user details.'
            ],
            [
                'name' => 'user.delete',
                'display_name' => 'Delete Users',
                'description' => 'Allows the user to remove back office users.'
            ],
            [
                'name' => 'user.view',
                'display_name' => 'View Users',
                'description' => 'Allows the user to view all user information.'
            ],
            [
                'name' => 'user.assign_roles',
                'display_name' => 'Assign Roles',
                'description' => 'Allows the user to assign roles to back office users.'
            ],
            [
                'name' => 'user.access_control',
                'display_name' => 'Manage Access Controls',
                'description' => 'Allows the user to configure role-based access controls.'
            ],
            [
                'name' => 'user.logs.view',
                'display_name' => 'View Login & Session Logs',
                'description' => 'Allows the user to view user login activity and session history.'
            ],
            [
                'name' => 'user.session.view',
                'display_name' => 'View Session Logs',
                'description' => 'Allows the user to view user session activity and history.'
            ],
            [
                'name' => 'user.toggle_status',
                'display_name' => 'Toggle User Status',
                'description' => 'Allows the user to activate or deactivate user accounts.'
            ],


            // Reports & Analytics
            [
                'name' => 'reports.view',
                'display_name' => 'View Reports Dashboard',
                'description' => 'Allows the user to access the main reports and analytics dashboard.'
            ],
            [
                'name' => 'reports.bookings',
                'display_name' => 'View Booking Reports',
                'description' => 'Allows the user to view booking and delivery performance reports.'
            ],
            [
                'name' => 'reports.performance',
                'display_name' => 'View Performance Reports',
                'description' => 'Allows the user to view driver and transporter performance metrics.'
            ],
            [
                'name' => 'reports.financial',
                'display_name' => 'View Financial Reports',
                'description' => 'Allows the user to view earnings, payments, and invoice summaries.'
            ],
            [
                'name' => 'reports.export',
                'display_name' => 'Export Reports',
                'description' => 'Allows the user to export reports in PDF or Excel formats.'
            ],
            [
                'name' => 'reports.view.charts',
                'display_name' => 'View Charts and Visual Data',
                'description' => 'Allows the user to access visual dashboards with trends and top performer charts.'
            ],
            // Profile Settings
            [
                'name' => 'profile.update',
                'display_name' => 'Update Profile',
                'description' => 'Allows the user to update their own profile information.'
            ],
            [
                'name' => 'profile.change_password',
                'display_name' => 'Change Password',
                'description' => 'Allows the user to reset or change their password.'
            ],
            [
                'name' => 'profile.2fa.enable',
                'display_name' => 'Enable Two-Factor Authentication',
                'description' => 'Allows the user to enable or manage 2FA settings.'
            ],
            [
                'name' => 'map.view.live',
                'display_name' => 'View Live Map',
                'description' => 'Allows the user to view live truck locations and route tracking.'
            ],
            [
                'name' => 'notifications.view',
                'display_name' => 'View Notifications',
                'description' => 'Allows the user to view system notifications and alerts.'
            ],
            [
                'name' => 'notifications.manage',
                'display_name' => 'Manage Notifications',
                'description' => 'Allows the user to manage and clear notifications.'
            ],
            [
                'name' => 'documents.view',
                'display_name' => 'View Document Vault',
                'description' => 'Allows the user to access the central document storage.'
            ],
            [
                'name' => 'documents.manage',
                'display_name' => 'Manage Documents',
                'description' => 'Allows the user to upload, delete, or organize documents in the vault.'
            ],
            [
                'name' => 'chat.access',
                'display_name' => 'Access Support Chat',
                'description' => 'Allows the user to use the internal chat system for team coordination.'
            ],


        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
