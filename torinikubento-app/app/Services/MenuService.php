<?php

namespace App\Services;

class MenuService
{
    /**
     * Get menu configuration for each role
     */
    public static function getMenuByRole(): array
    {
        return [
            'owner' => [
                [
                    'label' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                    'active_pattern' => 'dashboard',
                    'permission' => 'view_dashboard'
                ],
                [
                    'label' => 'Keuangan & Laporan',
                    'icon' => 'fas fa-chart-line',
                    'permission' => 'view_financial_reports',
                    'submenu' => [
                        [
                            'label' => 'Laporan Penjualan',
                            'route' => 'reports.sales',
                            'permission' => 'view_financial_reports'
                        ],
                        [
                            'label' => 'Laporan Pengeluaran',
                            'route' => 'reports.expenses',
                            'permission' => 'view_financial_reports'
                        ],
                        [
                            'label' => 'Laba Rugi',
                            'route' => 'reports.profit-loss',
                            'permission' => 'view_financial_reports'
                        ],
                        [
                            'label' => 'Analitik Tren',
                            'route' => 'reports.analytics',
                            'permission' => 'view_financial_reports'
                        ]
                    ]
                ],
                [
                    'label' => 'Manajemen',
                    'icon' => 'fas fa-users-cog',
                    'permission' => 'manage_users',
                    'submenu' => [
                        [
                            'label' => 'Karyawan',
                            'route' => 'users.index',
                            'permission' => 'manage_users'
                        ],
                        [
                            'label' => 'Menu & Harga',
                            'route' => 'menu.index',
                            'permission' => 'manage_menu'
                        ],
                        [
                            'label' => 'Promo & Loyalty',
                            'route' => 'promotions.index',
                            'permission' => 'manage_promotions'
                        ],
                        [
                            'label' => 'Supplier & Bahan Baku',
                            'route' => 'inventory.suppliers',
                            'permission' => 'manage_inventory'
                        ]
                    ]
                ],
                [
                    'label' => 'Monitoring Live',
                    'route' => 'monitoring.live',
                    'icon' => 'fas fa-eye',
                    'active_pattern' => 'monitoring.*',
                    'permission' => 'view_dashboard'
                ],
                [
                    'label' => 'Pengaturan',
                    'icon' => 'fas fa-cog',
                    'permission' => 'manage_settings',
                    'separator' => true,
                    'submenu' => [
                        [
                            'label' => 'Hak Akses',
                            'route' => 'settings.roles',
                            'permission' => 'manage_roles'
                        ],
                        [
                            'label' => 'Pengaturan Sistem',
                            'route' => 'settings.system',
                            'permission' => 'manage_settings'
                        ],
                        [
                            'label' => 'Backup & Restore',
                            'route' => 'settings.backup',
                            'permission' => 'manage_settings'
                        ]
                    ]
                ]
            ],
            'admin' => [
                [
                    'label' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                    'active_pattern' => 'dashboard',
                    'permission' => 'view_dashboard'
                ],
                [
                    'label' => 'Laporan Harian',
                    'icon' => 'fas fa-chart-bar',
                    'permission' => 'view_daily_reports',
                    'submenu' => [
                        [
                            'label' => 'Laporan Penjualan',
                            'route' => 'reports.daily-sales',
                            'permission' => 'view_daily_reports'
                        ],
                        [
                            'label' => 'Laporan Shift',
                            'route' => 'reports.shifts',
                            'permission' => 'view_daily_reports'
                        ]
                    ]
                ],
                [
                    'label' => 'Manajemen',
                    'icon' => 'fas fa-users',
                    'permission' => 'manage_users',
                    'submenu' => [
                        [
                            'label' => 'Karyawan',
                            'route' => 'users.index',
                            'permission' => 'manage_users'
                        ],
                        [
                            'label' => 'Menu & Harga',
                            'route' => 'menu.index',
                            'permission' => 'manage_menu'
                        ],
                        [
                            'label' => 'Inventori',
                            'route' => 'inventory.index',
                            'permission' => 'manage_inventory'
                        ]
                    ]
                ],
                [
                    'label' => 'Shift Management',
                    'route' => 'shifts.index',
                    'icon' => 'fas fa-clock',
                    'active_pattern' => 'shifts.*',
                    'permission' => 'manage_shifts'
                ]
            ],
            'kasir' => [
                [
                    'label' => 'POS',
                    'route' => 'pos.index',
                    'icon' => 'fas fa-cash-register',
                    'active_pattern' => 'pos.*',
                    'permission' => 'access_pos'
                ],
                [
                    'label' => 'Transaksi Hari Ini',
                    'route' => 'transactions.today',
                    'icon' => 'fas fa-receipt',
                    'active_pattern' => 'transactions.*',
                    'permission' => 'view_daily_transactions'
                ],
                [
                    'label' => 'Pembayaran',
                    'icon' => 'fas fa-credit-card',
                    'permission' => 'handle_payments',
                    'submenu' => [
                        [
                            'label' => 'Proses Pembayaran',
                            'route' => 'payments.process',
                            'permission' => 'handle_payments'
                        ],
                        [
                            'label' => 'History Pembayaran',
                            'route' => 'payments.history',
                            'permission' => 'handle_payments'
                        ]
                    ]
                ]
            ],
            'waiter' => [
                [
                    'label' => 'Meja',
                    'route' => 'tables.index',
                    'icon' => 'fas fa-table',
                    'active_pattern' => 'tables.*',
                    'permission' => 'manage_tables'
                ],
                [
                    'label' => 'Pesanan',
                    'icon' => 'fas fa-clipboard-list',
                    'permission' => 'create_orders',
                    'submenu' => [
                        [
                            'label' => 'Buat Pesanan',
                            'route' => 'orders.create',
                            'permission' => 'create_orders'
                        ],
                        [
                            'label' => 'Pesanan Aktif',
                            'route' => 'orders.active',
                            'permission' => 'update_order_status'
                        ]
                    ]
                ],
                [
                    'label' => 'Menu',
                    'route' => 'menu.view',
                    'icon' => 'fas fa-utensils',
                    'active_pattern' => 'menu.view',
                    'permission' => 'view_menu'
                ]
            ],
            'kitchen' => [
                [
                    'label' => 'Kitchen Display',
                    'route' => 'kitchen.display',
                    'icon' => 'fas fa-tv',
                    'active_pattern' => 'kitchen.*',
                    'permission' => 'view_kitchen_display'
                ],
                [
                    'label' => 'Pesanan Masak',
                    'route' => 'kitchen.orders',
                    'icon' => 'fas fa-fire',
                    'active_pattern' => 'kitchen.orders',
                    'permission' => 'update_order_status'
                ],
                [
                    'label' => 'Menu Items',
                    'route' => 'kitchen.menu',
                    'icon' => 'fas fa-list',
                    'active_pattern' => 'kitchen.menu',
                    'permission' => 'view_menu_items'
                ]
            ],
            'inventory' => [
                [
                    'label' => 'Dashboard Stok',
                    'route' => 'inventory.dashboard',
                    'icon' => 'fas fa-boxes',
                    'active_pattern' => 'inventory.dashboard',
                    'permission' => 'manage_inventory'
                ],
                [
                    'label' => 'Manajemen Stok',
                    'icon' => 'fas fa-warehouse',
                    'permission' => 'manage_inventory',
                    'submenu' => [
                        [
                            'label' => 'Update Stok',
                            'route' => 'inventory.update',
                            'permission' => 'update_stock_levels'
                        ],
                        [
                            'label' => 'Stock Opname',
                            'route' => 'inventory.opname',
                            'permission' => 'manage_inventory'
                        ]
                    ]
                ],
                [
                    'label' => 'Laporan Stok',
                    'route' => 'inventory.reports',
                    'icon' => 'fas fa-chart-pie',
                    'active_pattern' => 'inventory.reports',
                    'permission' => 'view_stock_reports'
                ]
            ],
            'supervisor' => [
                [
                    'label' => 'Dashboard Supervisor',
                    'route' => 'supervisor.dashboard',
                    'icon' => 'fas fa-user-tie',
                    'active_pattern' => 'supervisor.dashboard',
                    'permission' => 'view_shift_reports'
                ],
                [
                    'label' => 'Laporan Shift',
                    'route' => 'supervisor.shifts',
                    'icon' => 'fas fa-clock',
                    'active_pattern' => 'supervisor.shifts',
                    'permission' => 'view_shift_reports'
                ],
                [
                    'label' => 'Persetujuan',
                    'icon' => 'fas fa-check-circle',
                    'permission' => 'approve_voids',
                    'submenu' => [
                        [
                            'label' => 'Void Requests',
                            'route' => 'supervisor.voids',
                            'permission' => 'approve_voids'
                        ],
                        [
                            'label' => 'Refund Requests',
                            'route' => 'supervisor.refunds',
                            'permission' => 'approve_refunds'
                        ]
                    ]
                ],
                [
                    'label' => 'Summary Harian',
                    'route' => 'supervisor.daily-summary',
                    'icon' => 'fas fa-calendar-day',
                    'active_pattern' => 'supervisor.daily-summary',
                    'permission' => 'view_daily_summary'
                ]
            ]
        ];
    }

    /**
     * Get filtered menu items based on user's role and permissions
     */
    public static function getFilteredMenu($user): array
    {
        if (!$user || !$user->role) {
            return [];
        }

        $allMenus = self::getMenuByRole();
        $roleMenu = $allMenus[$user->role->name] ?? [];
        $userPermissions = $user->role->permissions ?? [];

        return array_filter(array_map(function ($menuItem) use ($userPermissions) {
            return self::filterMenuItem($menuItem, $userPermissions);
        }, $roleMenu), function ($item) {
            return $item !== null;
        });
    }

    /**
     * Filter individual menu item based on permissions
     */
    private static function filterMenuItem($menuItem, $userPermissions): ?array
    {
        // Check if user has permission for this menu item
        if (isset($menuItem['permission']) && !in_array($menuItem['permission'], $userPermissions)) {
            return null;
        }

        // Filter submenu items
        if (isset($menuItem['submenu'])) {
            $filteredSubmenu = array_filter(array_map(function ($subItem) use ($userPermissions) {
                return self::filterMenuItem($subItem, $userPermissions);
            }, $menuItem['submenu']), function ($item) {
                return $item !== null;
            });

            // If no submenu items are accessible, hide the parent menu
            if (empty($filteredSubmenu)) {
                return null;
            }

            $menuItem['submenu'] = array_values($filteredSubmenu);
        }

        return $menuItem;
    }
}
