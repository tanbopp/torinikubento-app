<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'performed_at'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'performed_at' => 'datetime'
    ];

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create a new audit log entry
     */
    public static function log(
        string $action,
        string $module,
        string $description,
        ?int $userId = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {
        self::create([
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'performed_at' => Carbon::now()
        ]);
    }

    /**
     * Log authentication events
     */
    public static function logAuth(string $action, ?int $userId = null, ?string $description = null): void
    {
        $descriptions = [
            'login_success' => 'User logged in successfully',
            'login_failed' => 'Failed login attempt',
            'logout' => 'User logged out',
            'account_locked' => 'Account locked due to multiple failed attempts',
            'password_changed' => 'User changed password',
            'pin_changed' => 'User changed PIN'
        ];

        self::log(
            $action,
            'auth',
            $description ?? $descriptions[$action] ?? $action,
            $userId
        );
    }

    /**
     * Log POS transactions
     */
    public static function logTransaction(string $action, ?array $transactionData = null, ?string $description = null): void
    {
        self::log($action, 'pos', $description ?? "Transaction $action", null, null, $transactionData);
    }

    /**
     * Log inventory changes
     */
    public static function logInventory(string $action, ?array $oldValues = null, ?array $newValues = null, ?string $description = null): void
    {
        self::log($action, 'inventory', $description ?? "Inventory $action", null, $oldValues, $newValues);
    }

    /**
     * Log menu changes
     */
    public static function logMenu(string $action, ?array $oldValues = null, ?array $newValues = null, ?string $description = null): void
    {
        self::log($action, 'menu', $description ?? "Menu $action", null, $oldValues, $newValues);
    }

    /**
     * Log user management actions
     */
    public static function logUserManagement(string $action, ?array $userData = null, ?string $description = null): void
    {
        self::log($action, 'users', $description ?? "User $action", null, null, $userData);
    }

    /**
     * Scope for specific module
     */
    public function scopeForModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope for specific user
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for date range
     */
    public function scopeForDateRange($query, Carbon $startDate, Carbon $endDate)
    {
        return $query->whereBetween('performed_at', [$startDate, $endDate]);
    }

    /**
     * Scope for today's logs
     */
    public function scopeToday($query)
    {
        return $query->whereDate('performed_at', Carbon::today());
    }
}
