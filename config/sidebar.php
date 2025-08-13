<?php

return [

    'Dashboard' => [
        'icon' => 'fas fa-tachometer-alt', // Icon for main category
        'item' => [
            [
                'label' => 'Dashboard',
                'route' => 'admin.dashboard',
                'icon'  => 'fas fa-tachometer-alt',
                'permission' => null, // everyone after login
            ],
        ]
    ],

    'Administration' => [
        'icon' => 'fas fa-cogs',
        'item' => [
            [
                'label' => 'Back Office Users',
                'route' => 'admin.backoffice-users.index',
                'icon'  => 'fas fa-user-cog',
                'role'  => 'Admin',
            ],
            [
                'label' => 'Roles & Permissions',
                'route' => 'admin.roles.index',
                'icon'  => 'fas fa-shield-alt',
                'role'  => 'Admin',
            ],
            [
                'label' => 'Profile Settings',
                'route' => 'profile.show',
                'icon'  => 'fas fa-user-shield',
                'permission' => null, // accessible to all
            ],
        ]
    ],

    'Driver Management' => [
        'icon' => 'fas fa-users',
        'item' => [
            [
                'label' => 'All Drivers',
                'route' => 'admin.drivers.index',
                'icon'  => 'fas fa-users',
                'permission' => 'driver.view',
            ],
            [
                'label' => 'Add New Driver',
                'route' => 'admin.drivers.create',
                'icon'  => 'fas fa-user-plus',
                'permission' => 'driver.create',
            ],
        ]
    ],

    'Transporter Management' => [
        'icon' => 'fas fa-truck-moving',
        'item' => [
            [
                'label' => 'All Transporters',
                'route' => 'admin.transporters.index',
                'icon'  => 'fas fa-truck-moving',
                'permission' => 'transporter.view',
            ],
            [
                'label' => 'Add New Transporter',
                'route' => 'admin.transporters.create',
                'icon'  => 'fas fa-truck-loading',
                'permission' => 'transporter.create',
            ],
            [
                'label' => 'Transporter Trucks',
                'route' => 'admin.transporters.trucks',
                'icon'  => 'fas fa-truck',
                'permission' => 'transporter.view',
            ],
        ]
    ],

    'Fleet & Load Management' => [
        'icon' => 'fas fa-boxes',
        'item' => [
            [
                'label' => 'All Loads',
                'route' => 'admin.loads.index',
                'icon'  => 'fas fa-boxes',
                'permission' => 'load.view',
            ],
            [
                'label' => 'Create New Load',
                'route' => 'admin.loads.create',
                'icon'  => 'fas fa-plus-square',
                'permission' => 'load.create',
            ],
            [
                'label' => 'Load Owners',
                'route' => 'admin.load_owners.index',
                'icon'  => 'fas fa-users',
                'permission' => 'load.view',
            ],
        ]
    ],

    'Bookings' => [
        'icon' => 'fas fa-clipboard-list',
        'item' => [
            [
                'label' => 'All Bookings',
                'route' => 'admin.bookings.index',
                'icon'  => 'fas fa-clipboard-list',
                'permission' => 'booking.view',
            ],
            [
                'label' => 'Create New Booking',
                'route' => 'admin.bookings.create',
                'icon'  => 'fas fa-plus-square',
                'permission' => 'booking.create',
            ],
            [
                'label' => 'Today’s Bookings',
                'route' => 'admin.bookings.today',
                'icon'  => 'fas fa-calendar-day',
                'permission' => 'booking.view',
            ],
        ]
    ],


    'Compliance & KYC' => [
        'icon' => 'fas fa-id-badge',
        'item' => [
            [
                'label' => 'KYC Dashboard',
                'route' => 'admin.kyc.index',
                'icon'  => 'fas fa-id-card',
                'permission' => 'kyc.view',
            ],
            [
                'label' => 'Expiring Documents',
                'route' => 'admin.kyc.expiries',
                'icon'  => 'fas fa-exclamation-triangle',
                'permission' => 'kyc.view',
            ],
        ]
    ],

    'Finance Management' => [
        'icon' => 'fas fa-file-invoice-dollar',
        'item' => [
            [
                'label' => 'Invoices',
                'route' => 'admin.finance.invoices',
                'icon'  => 'fas fa-file-invoice',
                'permission' => 'invoice.view',
            ],
            [
                'label' => 'Payments',
                'route' => 'admin.finance.payments',
                'icon'  => 'fas fa-money-check-alt',
                'permission' => 'invoice.payment.record',
            ],
        ]
    ],

    'Reports & Analytics' => [
        'icon' => 'fas fa-chart-bar',
        'item' => [
            [
                'label' => 'Booking Reports',
                'route' => 'admin.reports.operations',
                'icon'  => 'fas fa-chart-line',
                'permission' => 'reports.bookings',
            ],
            [
                'label' => 'Finance Reports',
                'route' => 'admin.reports.finance',
                'icon'  => 'fas fa-money-bill-wave',
                'permission' => 'reports.financial',
            ],
        ]
    ],

    'Notifications' => [
        'icon' => 'fas fa-bell',
        'items' => [
            [
                'label' => 'Notifications',
                'route' => 'admin.notifications.index',
                'icon'  => 'fas fa-bell',
                'permission' => null,
            ],
        ]
    ],

    'Trips & GPS' => [
        'icon' => 'fas fa-map-marker-alt',
        'items' => [
            [
                'label' => 'Live Trips',
                'route' => 'admin.trips.index',
                'icon'  => 'fas fa-map-marker-alt',
                'permission' => 'driver.trips.active',
            ],
            [
                'label' => 'Trip History',
                'route' => 'admin.trips.history',
                'icon'  => 'fas fa-history',
                'permission' => 'driver.trips.history',
            ],
        ]
    ],

];
