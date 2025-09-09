<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'name',
        'name_japanese',
        'description',
        'price_adjustment',
        'cost_adjustment',
        'type', // size, spice_level, custom
        'value', // small/medium/large, 1-5, custom_value
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'price_adjustment' => 'decimal:2',
        'cost_adjustment' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Get the product this variant belongs to
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get final price including adjustment
     */
    public function getFinalPrice(): float
    {
        return $this->product->getEffectivePrice() + $this->price_adjustment;
    }

    /**
     * Get final cost including adjustment
     */
    public function getFinalCost(): float
    {
        return $this->product->cost_price + $this->cost_adjustment;
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
