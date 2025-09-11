<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'name_japanese',
        'description',
        'allergen_info',
        'image_path',
        'base_price',
        'promo_price',
        'is_active',
        'is_available',
        'is_seasonal',
        'is_limited_edition',
        'daily_limit',
        'sort_order',
        'preparation_time',
        'nutritional_info',
        'spice_level',
        'season_start_date',
        'season_end_date',
        'cost_price',
        'margin_percentage',
        'popularity_score'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'promo_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'margin_percentage' => 'decimal:2',
        'is_active' => 'boolean',
        'is_available' => 'boolean',
        'is_seasonal' => 'boolean',
        'is_limited_edition' => 'boolean',
        'daily_limit' => 'integer',
        'sort_order' => 'integer',
        'preparation_time' => 'integer',
        'spice_level' => 'integer',
        'popularity_score' => 'integer',
        'nutritional_info' => 'array',
        'allergen_info' => 'array',
        'season_start_date' => 'date',
        'season_end_date' => 'date'
    ];

    /**
     * Get the category of this product
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the other costs associated with this product
     */
    public function otherCosts(): BelongsToMany
    {
        return $this->belongsToMany(OtherCost::class, 'product_other_costs')
                    ->withPivot('custom_value')
                    ->withTimestamps();
    }

    /**
     * Get product variants
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get product ingredients (BOM)
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'product_ingredients')
                    ->withPivot('quantity', 'unit', 'is_optional', 'cost_per_unit')
                    ->withTimestamps();
    }

    /**
     * Get product addons
     */
    public function addons(): BelongsToMany
    {
        return $this->belongsToMany(Addon::class, 'product_addons')
                    ->withPivot('price', 'is_default')
                    ->withTimestamps();
    }

    /**
     * Get bundle items if this is a bundle
     */
    public function bundleItems(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_bundles', 'bundle_id', 'product_id')
                    ->withPivot('quantity', 'is_required')
                    ->withTimestamps();
    }

    /**
     * Get bundles that include this product
     */
    public function bundles(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_bundles', 'product_id', 'bundle_id')
                    ->withPivot('quantity', 'is_required')
                    ->withTimestamps();
    }

    /**
     * Check if product is available today
     */
    public function isAvailableToday(): bool
    {
        if (!$this->is_active || !$this->is_available) {
            return false;
        }

        // Check if seasonal
        if ($this->is_seasonal) {
            $today = now()->toDateString();
            if ($this->season_start_date && $this->season_end_date) {
                return $today >= $this->season_start_date && $today <= $this->season_end_date;
            }
        }

        return true;
    }

    /**
     * Get effective price (promo if available, otherwise base)
     */
    public function getEffectivePrice(): float
    {
        return $this->promo_price && $this->promo_price < $this->base_price 
            ? $this->promo_price 
            : $this->base_price;
    }

    /**
     * Get price including tax
     */
    public function getPriceWithTax(): float
    {
        $basePrice = $this->getEffectivePrice();
        
        if ($this->tax && $this->tax->is_active) {
            if ($this->tax->is_inclusive) {
                return $basePrice;
            } else {
                return $basePrice + $this->tax->calculateTax($basePrice);
            }
        }
        
        return $basePrice;
    }

    /**
     * Get tax amount for this product
     */
    public function getTaxAmount(): float
    {
        $basePrice = $this->getEffectivePrice();
        
        if ($this->tax && $this->tax->is_active) {
            if ($this->tax->is_inclusive) {
                return $this->tax->calculateTax($this->tax->calculateBaseFromInclusive($basePrice));
            } else {
                return $this->tax->calculateTax($basePrice);
            }
        }
        
        return 0;
    }

    /**
     * Calculate food cost based on ingredients
     */
    public function calculateFoodCost(): float
    {
        $totalCost = 0;
        
        foreach ($this->ingredients as $ingredient) {
            $quantity = $ingredient->pivot->quantity;
            $costPerUnit = $ingredient->pivot->cost_per_unit ?? $ingredient->cost_per_unit;
            $totalCost += ($quantity * $costPerUnit);
        }

        return $totalCost;
    }

    /**
     * Calculate total other costs for this product
     */
    public function calculateOtherCosts(): float
    {
        $totalOtherCosts = 0;
        $ingredientCost = $this->calculateFoodCost();

        foreach ($this->otherCosts as $otherCost) {
            $costValue = $otherCost->pivot->custom_value ?? $otherCost->value;
            
            if ($otherCost->type === OtherCost::TYPE_PERCENTAGE) {
                $totalOtherCosts += $ingredientCost * ($costValue / 100);
            } else {
                $totalOtherCosts += $costValue;
            }
        }

        return $totalOtherCosts;
    }

    /**
     * Update cost price based on current ingredients and other costs
     */
    public function updateCostPrice(): void
    {
        // Calculate food cost from ingredients - always auto-calculate from BOM
        $ingredientCost = $this->calculateFoodCost();
        
        // Calculate other costs
        $otherCosts = $this->calculateOtherCosts();
        
        // Total cost price = ingredient cost + other costs
        $this->cost_price = $ingredientCost + $otherCosts;
        
        // Auto-update base_price if not manually set
        if ($this->base_price == 0 || $this->shouldAutoUpdateSellingPrice()) {
            $this->base_price = $this->cost_price;
        }
        
        // Calculate margin percentage
        $this->margin_percentage = $this->calculateMarginPercentage();
        
        $this->save();
    }

    /**
     * Check if selling price should be auto-updated
     * This returns true if the user hasn't manually set a selling price
     */
    protected function shouldAutoUpdateSellingPrice(): bool
    {
        // If base_price equals the previous cost_price, it means it was auto-set
        // and user hasn't manually changed it
        $originalCostPrice = $this->getOriginal('cost_price') ?? 0;
        return $this->base_price == $originalCostPrice;
    }

    /**
     * Auto update cost price whenever ingredients are attached/detached
     */
    public function recalculateCostPrice(): void
    {
        $this->refresh(); // Refresh model to get latest ingredient relationships
        $this->updateCostPrice();
    }

    /**
     * Calculate margin percentage
     */
    public function calculateMarginPercentage(): float
    {
        if ($this->base_price > 0 && $this->cost_price >= 0) {
            return (($this->base_price - $this->cost_price) / $this->base_price) * 100;
        }
        
        return 0;
    }

    /**
     * Get margin in rupiah
     */
    public function getMarginAmount(): float
    {
        return $this->base_price - $this->cost_price;
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeSeasonal($query)
    {
        return $query->where('is_seasonal', true);
    }

    public function scopeInSeason($query)
    {
        $today = now()->toDateString();
        return $query->where('is_seasonal', true)
                    ->where(function($q) use ($today) {
                        $q->whereNull('season_start_date')
                          ->orWhere('season_start_date', '<=', $today);
                    })
                    ->where(function($q) use ($today) {
                        $q->whereNull('season_end_date')
                          ->orWhere('season_end_date', '>=', $today);
                    });
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('popularity_score', 'desc');
    }

    public function scopeProfitable($query)
    {
        return $query->orderBy('margin_percentage', 'desc');
    }
}
