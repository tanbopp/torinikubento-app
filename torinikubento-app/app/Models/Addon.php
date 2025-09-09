<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Addon extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'name_japanese',
        'description',
        'base_price',
        'cost_price',
        'category', // topping, sauce, size_upgrade
        'is_active'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * Get products that can have this addon
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_addons')
                    ->withPivot('price', 'is_default')
                    ->withTimestamps();
    }

    /**
     * Get margin percentage
     */
    public function getMarginPercentage(): float
    {
        if ($this->base_price <= 0) {
            return 0;
        }
        
        return (($this->base_price - $this->cost_price) / $this->base_price) * 100;
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
