<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'permissions',
        'is_active'
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get users with this role
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if role has specific permission
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions ?? []);
    }

    /**
     * Add permission to role
     */
    public function addPermission(string $permission): void
    {
        $permissions = $this->permissions ?? [];
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->update(['permissions' => $permissions]);
        }
    }

    /**
     * Remove permission from role
     */
    public function removePermission(string $permission): void
    {
        $permissions = $this->permissions ?? [];
        $permissions = array_filter($permissions, fn($p) => $p !== $permission);
        $this->update(['permissions' => array_values($permissions)]);
    }

    /**
     * Get predefined restaurant roles
     */
    public static function getRestaurantRoles(): array
    {
        return [
            'owner' => [
                'display_name' => 'Owner',
                'description' => 'Full access to all features and reports',
                'permissions' => [
                    'view_dashboard',
                    'manage_users',
                    'manage_roles',
                    'view_financial_reports',
                    'manage_menu',
                    'manage_inventory',
                    'manage_promotions',
                    'manage_settings',
                    'void_transactions',
                    'access_audit_logs'
                ]
            ],
            'admin' => [
                'display_name' => 'Manager/Admin',
                'description' => 'Manage daily operations and staff',
                'permissions' => [
                    'view_dashboard',
                    'manage_users',
                    'manage_roles',
                    'view_daily_reports',
                    'manage_menu',
                    'manage_inventory',
                    'manage_shifts',
                    'void_transactions',
                    'handle_refunds'
                ]
            ],
            'kasir' => [
                'display_name' => 'Kasir',
                'description' => 'Handle POS transactions and payments',
                'permissions' => [
                    'access_pos',
                    'create_transactions',
                    'print_receipts',
                    'view_daily_transactions',
                    'handle_payments'
                ]
            ],
            'waiter' => [
                'display_name' => 'Waiter/Pelayan',
                'description' => 'Take orders and manage tables',
                'permissions' => [
                    'manage_tables',
                    'create_orders',
                    'update_order_status',
                    'view_menu'
                ]
            ],
            'kitchen' => [
                'display_name' => 'Kitchen Staff',
                'description' => 'View and update food preparation status',
                'permissions' => [
                    'view_kitchen_display',
                    'update_order_status',
                    'view_menu_items'
                ]
            ],
            'inventory' => [
                'display_name' => 'Inventory Staff',
                'description' => 'Manage stock and inventory',
                'permissions' => [
                    'manage_inventory',
                    'view_stock_reports',
                    'update_stock_levels'
                ]
            ],
            'supervisor' => [
                'display_name' => 'Supervisor',
                'description' => 'Supervise shifts and approve special actions',
                'permissions' => [
                    'view_shift_reports',
                    'approve_voids',
                    'approve_refunds',
                    'view_daily_summary'
                ]
            ]
        ];
    }
}
