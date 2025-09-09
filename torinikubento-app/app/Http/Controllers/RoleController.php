<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of roles
     */
    public function index()
    {
        $currentUser = Auth::user();
        
        // Check permission
        if (!in_array($currentUser->role->name, ['owner', 'admin'])) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        $roles = Role::withCount('users')->orderBy('created_at', 'desc')->get();
        
        // Statistics
        $stats = [
            'total_roles' => Role::count(),
            'active_roles' => Role::where('is_active', true)->count(),
            'inactive_roles' => Role::where('is_active', false)->count(),
            'system_roles' => Role::whereIn('name', ['owner', 'admin'])->count(),
        ];

        return view('main.manage-roles.index', compact('roles', 'stats', 'currentUser'));
    }

    /**
     * Show the form for creating a new role
     */
    public function create()
    {
        $currentUser = Auth::user();
        
        // Check permission
        if (!in_array($currentUser->role->name, ['owner', 'admin'])) {
            return redirect()->route('roles.index')->with('error', 'Akses ditolak');
        }

        $availablePermissions = $this->getAvailablePermissions();
        
        return view('main.manage-roles.create', compact('availablePermissions', 'currentUser'));
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();
        
        // Check permission
        if (!in_array($currentUser->role->name, ['owner', 'admin'])) {
            return redirect()->route('roles.index')->with('error', 'Akses ditolak');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name|alpha_dash',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $role = Role::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'permissions' => $request->permissions ?? [],
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan');
    }

    /**
     * Display the specified role
     */
    public function show(Role $role)
    {
        $currentUser = Auth::user();
        
        // Check permission
        if (!in_array($currentUser->role->name, ['owner', 'admin'])) {
            return redirect()->route('roles.index')->with('error', 'Akses ditolak');
        }

        $role->load('users');
        $availablePermissions = $this->getAvailablePermissions();
        
        return view('main.manage-roles.show', compact('role', 'availablePermissions', 'currentUser'));
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit(Role $role)
    {
        $currentUser = Auth::user();
        
        // Check permission
        if (!in_array($currentUser->role->name, ['owner', 'admin'])) {
            return redirect()->route('roles.index')->with('error', 'Akses ditolak');
        }

        // Prevent editing system roles by non-owner
        if (in_array($role->name, ['owner', 'admin']) && $currentUser->role->name !== 'owner') {
            return redirect()->route('roles.index')->with('error', 'Hanya owner yang dapat mengedit role sistem');
        }

        $availablePermissions = $this->getAvailablePermissions();
        
        return view('main.manage-roles.edit', compact('role', 'availablePermissions', 'currentUser'));
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, Role $role)
    {
        $currentUser = Auth::user();
        
        // Check permission
        if (!in_array($currentUser->role->name, ['owner', 'admin'])) {
            return redirect()->route('roles.index')->with('error', 'Akses ditolak');
        }

        // Prevent editing system roles by non-owner
        if (in_array($role->name, ['owner', 'admin']) && $currentUser->role->name !== 'owner') {
            return redirect()->route('roles.index')->with('error', 'Hanya owner yang dapat mengedit role sistem');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|alpha_dash|unique:roles,name,' . $role->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $role->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'permissions' => $request->permissions ?? [],
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui');
    }

    /**
     * Remove the specified role
     */
    public function destroy(Role $role)
    {
        $currentUser = Auth::user();
        
        // Check permission
        if (!in_array($currentUser->role->name, ['owner', 'admin'])) {
            return redirect()->route('roles.index')->with('error', 'Akses ditolak');
        }

        // Prevent deleting system roles
        if (in_array($role->name, ['owner', 'admin'])) {
            return redirect()->route('roles.index')->with('error', 'Role sistem tidak dapat dihapus');
        }

        // Check if role is being used by any users
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')->with('error', 'Role tidak dapat dihapus karena masih digunakan oleh user');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus');
    }

    /**
     * Get all available permissions
     */
    private function getAvailablePermissions()
    {
        return [
            'dashboard' => [
                'label' => 'Dashboard',
                'permissions' => [
                    'view_dashboard' => 'Lihat Dashboard'
                ]
            ],
            'users' => [
                'label' => 'Manajemen User',
                'permissions' => [
                    'manage_users' => 'Kelola User',
                    'view_users' => 'Lihat User',
                    'create_users' => 'Tambah User',
                    'edit_users' => 'Edit User',
                    'delete_users' => 'Hapus User'
                ]
            ],
            'roles' => [
                'label' => 'Manajemen Role',
                'permissions' => [
                    'manage_roles' => 'Kelola Role',
                    'view_roles' => 'Lihat Role',
                    'create_roles' => 'Tambah Role',
                    'edit_roles' => 'Edit Role',
                    'delete_roles' => 'Hapus Role'
                ]
            ],
            'pos' => [
                'label' => 'Point of Sale',
                'permissions' => [
                    'access_pos' => 'Akses POS',
                    'create_transactions' => 'Buat Transaksi',
                    'print_receipts' => 'Cetak Struk',
                    'view_daily_transactions' => 'Lihat Transaksi Harian',
                    'handle_payments' => 'Proses Pembayaran'
                ]
            ],
            'orders' => [
                'label' => 'Manajemen Pesanan',
                'permissions' => [
                    'manage_tables' => 'Kelola Meja',
                    'create_orders' => 'Buat Pesanan',
                    'update_order_status' => 'Update Status Pesanan',
                    'view_orders' => 'Lihat Pesanan'
                ]
            ],
            'menu' => [
                'label' => 'Manajemen Menu',
                'permissions' => [
                    'manage_menu' => 'Kelola Menu',
                    'view_menu' => 'Lihat Menu',
                    'create_menu_items' => 'Tambah Item Menu',
                    'edit_menu_items' => 'Edit Item Menu',
                    'delete_menu_items' => 'Hapus Item Menu'
                ]
            ],
            'inventory' => [
                'label' => 'Manajemen Inventory',
                'permissions' => [
                    'manage_inventory' => 'Kelola Inventory',
                    'view_stock_reports' => 'Lihat Laporan Stock',
                    'update_stock_levels' => 'Update Level Stock'
                ]
            ],
            'kitchen' => [
                'label' => 'Kitchen',
                'permissions' => [
                    'view_kitchen_display' => 'Lihat Display Dapur',
                    'update_food_status' => 'Update Status Makanan'
                ]
            ],
            'reports' => [
                'label' => 'Laporan',
                'permissions' => [
                    'view_financial_reports' => 'Lihat Laporan Keuangan',
                    'view_daily_reports' => 'Lihat Laporan Harian',
                    'view_shift_reports' => 'Lihat Laporan Shift',
                    'view_daily_summary' => 'Lihat Ringkasan Harian'
                ]
            ],
            'transactions' => [
                'label' => 'Transaksi',
                'permissions' => [
                    'void_transactions' => 'Batalkan Transaksi',
                    'handle_refunds' => 'Proses Refund',
                    'approve_voids' => 'Approve Pembatalan',
                    'approve_refunds' => 'Approve Refund'
                ]
            ],
            'system' => [
                'label' => 'Sistem',
                'permissions' => [
                    'manage_settings' => 'Kelola Pengaturan',
                    'access_audit_logs' => 'Akses Log Audit',
                    'manage_shifts' => 'Kelola Shift'
                ]
            ]
        ];
    }
}
