<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tax extends Model
{
    use SoftDeletes;

    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED = 'fixed';

    protected $fillable = [
        'name',
        'code',
        'type',
        'rate',
        'description',
        'is_active',
        'is_inclusive'
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'is_active' => 'boolean',
        'is_inclusive' => 'boolean'
    ];

    /**
     * Calculate tax amount for given base amount
     */
    public function calculateTax(float $baseAmount): float
    {
        if ($this->type === self::TYPE_PERCENTAGE) {
            return $baseAmount * ($this->rate / 100);
        }
        
        return $this->rate;
    }

    /**
     * Calculate base amount from inclusive tax
     */
    public function calculateBaseFromInclusive(float $inclusiveAmount): float
    {
        if ($this->type === self::TYPE_PERCENTAGE && $this->is_inclusive) {
            return $inclusiveAmount / (1 + ($this->rate / 100));
        }
        
        return $inclusiveAmount - $this->rate;
    }

    /**
     * Get available tax types
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
