<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtherCost extends Model
{
    use SoftDeletes;

    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED = 'fixed';

    protected $fillable = [
        'name',
        'type',
        'value',
        'description',
        'is_active'
    ];

    protected $casts = [
        'value' => 'decimal:4',
        'is_active' => 'boolean'
    ];

    /**
     * Calculate cost amount for given base amount
     */
    public function calculateCost(float $baseAmount): float
    {
        if ($this->type === self::TYPE_PERCENTAGE) {
            return $baseAmount * ($this->value / 100);
        }
        
        return $this->value;
    }

    /**
     * Get available types
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_PERCENTAGE => 'Percentage',
            self::TYPE_FIXED => 'Fixed Amount'
        ];
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
