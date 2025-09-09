<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class UserSession extends Model
{
    protected $fillable = [
        'user_id',
        'session_token',
        'device_type',
        'device_name',
        'ip_address',
        'user_agent',
        'login_at',
        'last_activity_at',
        'logout_at',
        'is_active',
        'timeout_minutes'
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'logout_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    /**
     * Get the user that owns this session
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if session is expired
     */
    public function isExpired(): bool
    {
        if (!$this->is_active) {
            return true;
        }

        $timeoutMinutes = $this->timeout_minutes ?? 60;
        $expiredAt = $this->last_activity_at->addMinutes($timeoutMinutes);
        
        return Carbon::now()->isAfter($expiredAt);
    }

    /**
     * Update last activity
     */
    public function updateActivity(): void
    {
        $this->update(['last_activity_at' => Carbon::now()]);
    }

    /**
     * End session
     */
    public function endSession(): void
    {
        $this->update([
            'logout_at' => Carbon::now(),
            'is_active' => false
        ]);
    }

    /**
     * Generate unique session token
     */
    public static function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Scope for active sessions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for expired sessions
     */
    public function scopeExpired($query)
    {
        return $query->where(function ($q) {
            $q->where('is_active', false)
              ->orWhereRaw('DATE_ADD(last_activity_at, INTERVAL timeout_minutes MINUTE) < NOW()');
        });
    }
}
