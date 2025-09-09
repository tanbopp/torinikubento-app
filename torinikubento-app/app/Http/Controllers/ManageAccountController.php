<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\AuditLog;
use App\Models\UserSession;

class ManageAccountController extends Controller
{
    /**
     * Display user management page
     */
    public function index()
    {
        $currentUser = Auth::user();
        
        // Check permission - only owner, manager, admin can access
        if (!in_array($currentUser->role->name, ['owner', 'manager', 'admin'])) {
            abort(403, 'Unauthorized access');
        }
        
        // Get all users with their roles and sessions
        $users = User::with(['role', 'userSessions' => function($query) {
                $query->where('is_active', true)->latest('last_activity_at');
            }])
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get all roles for dropdown
        $roles = Role::orderBy('name')->get();
        
        // Get online users count
        $onlineUsersCount = UserSession::where('is_active', true)
            ->where('last_activity_at', '>=', now()->subMinutes(5))
            ->count();
            
        // Get user statistics
        $stats = [
            'total_users' => $users->count(),
            'active_users' => $users->where('is_active', true)->count(),
            'inactive_users' => $users->where('is_active', false)->count(),
            'online_users' => $onlineUsersCount,
            'users_by_role' => $users->groupBy('role.name')->map->count()
        ];
        
        return view('main.manage-accounts.index', compact('users', 'roles', 'stats', 'currentUser'));
    }
    
    /**
     * Show create user form
     */
    public function create()
    {
        $currentUser = Auth::user();
        
        // Only owner and admin can create users
        if (!in_array($currentUser->role->name, ['owner', 'admin'])) {
            abort(403, 'Unauthorized access');
        }
        
        $roles = Role::orderBy('name')->get();
        
        return view('main.manage-accounts.create', compact('roles', 'currentUser'));
    }
    
    /**
     * Store new user
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();
        
        // Only owner and admin can create users
        if (!in_array($currentUser->role->name, ['owner', 'admin'])) {
            abort(403, 'Unauthorized access');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|string|min:6|confirmed',
            'is_active' => 'boolean'
        ]);
        
        try {
            DB::beginTransaction();
            
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'password' => Hash::make($request->password),
                'is_active' => $request->boolean('is_active', true),
                'force_password_change' => true // Force password change on first login
            ]);
            
            // Log the activity
            AuditLog::create([
                'user_id' => $currentUser->id,
                'action' => 'create_user',
                'table_name' => 'users',
                'record_id' => $user->id,
                'old_values' => null,
                'new_values' => json_encode([
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role_id' => $user->role_id,
                    'is_active' => $user->is_active
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            DB::commit();
            
            return redirect()->route('manage-accounts.index')
                ->with('success', 'User berhasil dibuat. User akan diminta mengganti password saat login pertama.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat user: ' . $e->getMessage());
        }
    }
    
    /**
     * Show edit user form
     */
    public function edit($id)
    {
        $currentUser = Auth::user();
        
        // Check permission
        if (!in_array($currentUser->role->name, ['owner', 'admin'])) {
            // Manager can only request - redirect to show with edit request mode
            if ($currentUser->role->name === 'manager') {
                return redirect()->route('manage-accounts.show', $id)
                    ->with('info', 'Sebagai Manager, Anda dapat mengajukan perubahan kepada Admin.');
            }
            abort(403, 'Unauthorized access');
        }
        
        $user = User::with('role')->findOrFail($id);
        $roles = Role::orderBy('name')->get();
        
        return view('main.manage-accounts.edit', compact('user', 'roles', 'currentUser'));
    }
    
    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $currentUser = Auth::user();
        
        // Only owner and admin can update users
        if (!in_array($currentUser->role->name, ['owner', 'admin'])) {
            abort(403, 'Unauthorized access');
        }
        
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'boolean',
            'reset_password' => 'boolean'
        ]);
        
        try {
            DB::beginTransaction();
            
            $oldValues = [
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'role_id' => $user->role_id,
                'is_active' => $user->is_active
            ];
            
            // Update user data
            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'is_active' => $request->boolean('is_active', true)
            ]);
            
            // Reset password if requested
            if ($request->boolean('reset_password')) {
                $newPassword = 'password123'; // Default password
                $user->update([
                    'password' => Hash::make($newPassword),
                    'force_password_change' => true
                ]);
            }
            
            $newValues = [
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'role_id' => $user->role_id,
                'is_active' => $user->is_active
            ];
            
            // Log the activity
            AuditLog::create([
                'user_id' => $currentUser->id,
                'action' => 'update_user',
                'table_name' => 'users',
                'record_id' => $user->id,
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($newValues),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            DB::commit();
            
            $message = 'Data user berhasil diperbarui.';
            if ($request->boolean('reset_password')) {
                $message .= ' Password telah direset ke "password123" dan user akan diminta mengganti password saat login.';
            }
            
            return redirect()->route('manage-accounts.index')->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }
    }
    
    /**
     * Show user details
     */
    public function show($id)
    {
        $currentUser = Auth::user();
        
        // Check permission
        if (!in_array($currentUser->role->name, ['owner', 'manager', 'admin'])) {
            abort(403, 'Unauthorized access');
        }
        
        $user = User::with(['role', 'userSessions' => function($query) {
                $query->orderBy('last_activity_at', 'desc')->take(10);
            }])
            ->findOrFail($id);
            
        // Get user's recent activities from audit log
        $recentActivities = AuditLog::where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
            
        // Get user's active session
        $activeSession = UserSession::where('user_id', $id)
            ->where('is_active', true)
            ->first();
            
        return view('main.manage-accounts.show', compact('user', 'recentActivities', 'activeSession', 'currentUser'));
    }
    
    /**
     * Deactivate user
     */
    public function deactivate($id)
    {
        $currentUser = Auth::user();
        
        // Only owner and admin can deactivate users
        if (!in_array($currentUser->role->name, ['owner', 'admin'])) {
            abort(403, 'Unauthorized access');
        }
        
        try {
            DB::beginTransaction();
            
            $user = User::findOrFail($id);
            
            // Can't deactivate yourself
            if ($user->id === $currentUser->id) {
                return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
            }
            
            $user->update(['is_active' => false]);
            
            // Force logout user by deactivating all sessions
            UserSession::where('user_id', $user->id)->update(['is_active' => false]);
            
            // Log the activity
            AuditLog::create([
                'user_id' => $currentUser->id,
                'action' => 'deactivate_user',
                'table_name' => 'users',
                'record_id' => $user->id,
                'old_values' => json_encode(['is_active' => true]),
                'new_values' => json_encode(['is_active' => false]),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'User berhasil dinonaktifkan dan telah logout dari sistem.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menonaktifkan user: ' . $e->getMessage());
        }
    }
    
    /**
     * Reactivate user
     */
    public function reactivate($id)
    {
        $currentUser = Auth::user();
        
        // Only owner and admin can reactivate users
        if (!in_array($currentUser->role->name, ['owner', 'admin'])) {
            abort(403, 'Unauthorized access');
        }
        
        try {
            DB::beginTransaction();
            
            $user = User::findOrFail($id);
            $user->update(['is_active' => true]);
            
            // Log the activity
            AuditLog::create([
                'user_id' => $currentUser->id,
                'action' => 'reactivate_user',
                'table_name' => 'users',
                'record_id' => $user->id,
                'old_values' => json_encode(['is_active' => false]),
                'new_values' => json_encode(['is_active' => true]),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'User berhasil diaktifkan kembali.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal mengaktifkan user: ' . $e->getMessage());
        }
    }
    
    /**
     * Force logout user
     */
    public function forceLogout($id)
    {
        $currentUser = Auth::user();
        
        // Only owner, manager, and admin can force logout
        if (!in_array($currentUser->role->name, ['owner', 'manager', 'admin'])) {
            abort(403, 'Unauthorized access');
        }
        
        try {
            $user = User::findOrFail($id);
            
            // Force logout by deactivating all sessions
            $affectedSessions = UserSession::where('user_id', $user->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
            
            // Log the activity
            AuditLog::create([
                'user_id' => $currentUser->id,
                'action' => 'force_logout_user',
                'table_name' => 'user_sessions',
                'record_id' => $user->id,
                'old_values' => null,
                'new_values' => json_encode(['sessions_terminated' => $affectedSessions]),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return redirect()->back()->with('success', "User {$user->name} berhasil di-logout paksa dari {$affectedSessions} sesi aktif.");
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal melakukan force logout: ' . $e->getMessage());
        }
    }
    
    /**
     * Delete user (soft delete, keep for audit)
     */
    public function destroy($id)
    {
        $currentUser = Auth::user();
        
        // Only owner can delete users
        if ($currentUser->role->name !== 'owner') {
            abort(403, 'Hanya Owner yang dapat menghapus akun user.');
        }
        
        try {
            DB::beginTransaction();
            
            $user = User::findOrFail($id);
            
            // Can't delete yourself
            if ($user->id === $currentUser->id) {
                return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            }
            
            // Store user data for audit log before deletion
            $userData = [
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'role_id' => $user->role_id
            ];
            
            // Deactivate all sessions
            UserSession::where('user_id', $user->id)->update(['is_active' => false]);
            
            // Soft delete user
            $user->delete();
            
            // Log the activity
            AuditLog::create([
                'user_id' => $currentUser->id,
                'action' => 'delete_user',
                'table_name' => 'users',
                'record_id' => $user->id,
                'old_values' => json_encode($userData),
                'new_values' => json_encode(['deleted' => true]),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            DB::commit();
            
            return redirect()->route('manage-accounts.index')->with('success', 'User berhasil dihapus dari sistem.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}
