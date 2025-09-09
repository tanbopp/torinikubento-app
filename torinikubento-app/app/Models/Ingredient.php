<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{
    use SoftDeletes;

    const TYPE_FRESH = 'fresh';
    const TYPE_DRY = 'dry';
    const TYPE_FROZEN = 'frozen';
    const TYPE_LIQUID = 'liquid';

    const UNIT_GRAM = 'gram';
    const UNIT_ML = 'ml';
    const UNIT_PCS = 'pcs';
    const UNIT_KG = 'kg';
    const UNIT_LITER = 'liter';

    protected $fillable = [
        'name',
        'name_japanese',
        'description',
        'type',
        'unit',
        'cost_per_unit',
        'current_stock',
        'minimum_stock',
        'maximum_stock',
        'supplier_info',
        'shelf_life_days',
        'storage_instruction',
        'allergen_info',
        'nutritional_value',
        'is_active'
    ];

    protected $casts = [
        'cost_per_unit' => 'decimal:4',
        'current_stock' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'maximum_stock' => 'decimal:2',
        'shelf_life_days' => 'integer',
        'supplier_info' => 'array',
        'allergen_info' => 'array',
        'nutritional_value' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get products that use this ingredient
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_ingredients')
                    ->withPivot('quantity', 'unit', 'is_optional', 'cost_per_unit')
                    ->withTimestamps();
    }

    /**
     * Get ingredient stock entries
     */
    public function stockEntries()
    {
        return $this->hasMany(IngredientStock::class);
    }

    /**
     * Check if ingredient is low stock
     */
    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->minimum_stock;
    }

    /**
     * Check if ingredient is expired or near expiry
     */
    public function isNearExpiry(int $days = 3): bool
    {
        if ($this->type !== self::TYPE_FRESH) {
            return false;
        }

        return $this->stockEntries()
                    ->where('expiry_date', '<=', now()->addDays($days))
                    ->where('quantity', '>', 0)
                    ->exists();
    }

    /**
     * Get available types
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_FRESH => 'Fresh (Perishable)',
            self::TYPE_DRY => 'Dry/Non-perishable',
            self::TYPE_FROZEN => 'Frozen',
            self::TYPE_LIQUID => 'Liquid'
        ];
    }

    /**
     * Get available units
     */
    public static function getUnits(): array
    {
        return [
            self::UNIT_GRAM => 'Gram',
            self::UNIT_KG => 'Kilogram',
            self::UNIT_ML => 'Milliliter',
            self::UNIT_LITER => 'Liter',
            self::UNIT_PCS => 'Pieces'
        ];
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('current_stock <= minimum_stock');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeFresh($query)
    {
        return $query->where('type', self::TYPE_FRESH);
    }

    public function scopeNearExpiry($query, $days = 3)
    {
        return $query->whereHas('stockEntries', function($q) use ($days) {
            $q->where('expiry_date', '<=', now()->addDays($days))
              ->where('quantity', '>', 0);
        });
    }
}
