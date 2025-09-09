<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSession;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Show owner login form
     */
    public function showOwnerLogin()
    {
        return view('auth.owner');
    }

    /**
     * Show manager login form
     */
    public function showManagerLogin()
    {
        return view('auth.manager');
    }

    /**
     * Show chef login form
     */
    public function showChefLogin()
    {
        return view('auth.chef');
    }

    /**
     * Show waiter login form
     */
    public function showWaiterLogin()
    {
        return view('auth.waiter');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_type' => 'required|in:username,pin',
            'identifier' => 'required|string',
            'password' => 'required_if:login_type,username|string',
            'pin' => 'required_if:login_type,pin|string|min:4|max:6',
            'device_type' => 'nullable|string',
            'device_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $loginType = $request->login_type;
        $identifier = $request->identifier;
        
        // Find user by username or email
        $user = User::where(function ($query) use ($identifier) {
            $query->where('username', $identifier)
                  ->orWhere('email', $identifier);
        })->with('role')->first();

        if (!$user) {
            AuditLog::logAuth('login_failed', null, "Login attempt with identifier: $identifier");
            return back()->withErrors(['identifier' => 'User not found.'])->withInput();
        }

        // Check if account is active
        if (!$user->is_active) {
            AuditLog::logAuth('login_failed', $user->id, 'Account is inactive');
            return back()->withErrors(['identifier' => 'Account is inactive.'])->withInput();
        }

        // Check if account is locked
        if ($user->isLocked()) {
            AuditLog::logAuth('login_failed', $user->id, 'Account is locked');
            return back()->withErrors(['identifier' => 'Account is locked. Please try again later.'])->withInput();
        }

        // Verify credentials
        $credentialsValid = false;
        if ($loginType === 'username') {
            $credentialsValid = Hash::check($request->password, $user->password);
        } elseif ($loginType === 'pin') {
            $credentialsValid = $user->pin && Hash::check($request->pin, $user->pin);
        }

        if (!$credentialsValid) {
            $user->incrementLoginAttempts();
            AuditLog::logAuth('login_failed', $user->id, "Invalid credentials for $loginType login");
            return back()->withErrors(['password' => 'Invalid credentials.'])->withInput();
        }

        // Check role if specified
        if ($request->has('expected_role')) {
            $expectedRole = $request->expected_role;
            if (!$user->role || $user->role->name !== $expectedRole) {
                AuditLog::logAuth('login_failed', $user->id, "Role mismatch. Expected: $expectedRole, Got: " . ($user->role->name ?? 'none'));
                return back()->withErrors(['identifier' => 'Anda tidak memiliki akses untuk role ini.'])->withInput();
            }
        }

        // Login successful
        $user->resetLoginAttempts();
        Auth::login($user);

        // Create user session
        $this->createUserSession($user, $request);

        // Log successful login
        AuditLog::logAuth('login_success', $user->id);

        return redirect()->intended($this->getRedirectPath($user));
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // End current session
            $this->endCurrentSession($user);
            
            // Log logout
            AuditLog::logAuth('logout', $user->id);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Successfully logged out.');
    }

    /**
     * Create user session record
     */
    private function createUserSession(User $user, Request $request): void
    {
        // End any existing active sessions for this device
        UserSession::where('user_id', $user->id)
            ->where('ip_address', $request->ip())
            ->where('is_active', true)
            ->update(['is_active' => false, 'logout_at' => Carbon::now()]);

        // Create new session
        UserSession::create([
            'user_id' => $user->id,
            'session_token' => UserSession::generateToken(),
            'device_type' => $request->device_type ?? $this->detectDeviceType($request),
            'device_name' => $request->device_name ?? $this->detectDeviceName($request),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_at' => Carbon::now(),
            'last_activity_at' => Carbon::now(),
            'is_active' => true,
            'timeout_minutes' => $this->getSessionTimeout($user)
        ]);
    }

    /**
     * End current user session
     */
    private function endCurrentSession(User $user): void
    {
        UserSession::where('user_id', $user->id)
            ->where('is_active', true)
            ->update([
                'logout_at' => Carbon::now(),
                'is_active' => false
            ]);
    }

    /**
     * Get redirect path based on user role
     */
    private function getRedirectPath(User $user): string
    {
        if (!$user->role) {
            return '/dashboard';
        }

        switch ($user->role->name) {
            case 'owner':
                return '/dashboard';
            case 'admin':
                return '/admin/dashboard';
            case 'kasir':
                return '/pos';
            case 'waiter':
                return '/waiter/tables';
            case 'kitchen':
                return '/kitchen/orders';
            case 'inventory':
                return '/inventory';
            case 'supervisor':
                return '/supervisor/dashboard';
            default:
                return '/dashboard';
        }
    }

    /**
     * Detect device type from user agent
     */
    private function detectDeviceType(Request $request): string
    {
        $userAgent = $request->userAgent();
        
        if (str_contains($userAgent, 'Mobile') || str_contains($userAgent, 'Android')) {
            return 'mobile';
        } elseif (str_contains($userAgent, 'Tablet') || str_contains($userAgent, 'iPad')) {
            return 'tablet';
        } else {
            return 'pc';
        }
    }

    /**
     * Detect device name from user agent
     */
    private function detectDeviceName(Request $request): string
    {
        $userAgent = $request->userAgent();
        
        // Simple device name detection
        if (str_contains($userAgent, 'Windows')) {
            return 'Windows PC';
        } elseif (str_contains($userAgent, 'Mac')) {
            return 'Mac';
        } elseif (str_contains($userAgent, 'Android')) {
            return 'Android Device';
        } elseif (str_contains($userAgent, 'iPad')) {
            return 'iPad';
        } elseif (str_contains($userAgent, 'iPhone')) {
            return 'iPhone';
        } else {
            return 'Unknown Device';
        }
    }

    /**
     * Get session timeout based on user role
     */
    private function getSessionTimeout(User $user): int
    {
        if (!$user->role) {
            return 60; // Default 60 minutes
        }

        switch ($user->role->name) {
            case 'owner':
            case 'admin':
                return 120; // 2 hours
            case 'supervisor':
                return 90;  // 1.5 hours
            case 'kasir':
            case 'waiter':
                return 60;  // 1 hour
            case 'kitchen':
                return 240; // 4 hours (kitchen shift)
            case 'inventory':
                return 60;  // 1 hour
            default:
                return 60;
        }
    }

    /**
     * Show PIN login form (for quick access)
     */
    public function showPinLogin()
    {
        return view('auth.pin-login');
    }

    /**
     * Handle PIN-only login (for registered devices)
     */
    public function pinLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'pin' => 'required|string|min:4|max:6'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('username', $request->username)
                   ->where('is_active', true)
                   ->with('role')
                   ->first();

        if (!$user || !$user->pin || !Hash::check($request->pin, $user->pin)) {
            return back()->withErrors(['pin' => 'Invalid username or PIN.']);
        }

        if ($user->isLocked()) {
            return back()->withErrors(['pin' => 'Account is locked.']);
        }

        $user->resetLoginAttempts();
        Auth::login($user);
        $this->createUserSession($user, $request);
        AuditLog::logAuth('login_success', $user->id, 'PIN login');

        return redirect($this->getRedirectPath($user));
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        /** @var User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        AuditLog::logAuth('password_changed', $user->id);

        return back()->with('success', 'Password changed successfully.');
    }

    /**
     * Change PIN
     */
    public function changePin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_pin' => 'required_if:has_pin,true',
            'new_pin' => 'required|string|min:4|max:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        /** @var User $user */
        $user = Auth::user();

        // If user already has PIN, verify current PIN
        if ($user->pin && !Hash::check($request->current_pin, $user->pin)) {
            return back()->withErrors(['current_pin' => 'Current PIN is incorrect.']);
        }

        $user->setPin($request->new_pin);
        AuditLog::logAuth('pin_changed', $user->id);

        return back()->with('success', 'PIN changed successfully.');
    }
}
