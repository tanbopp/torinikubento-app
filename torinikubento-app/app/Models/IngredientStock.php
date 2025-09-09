<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IngredientStock extends Model
{
    protected $fillable = [
        'ingredient_id',
        'batch_number',
        'quantity',
        'unit_cost',
        'supplier',
        'purchase_date',
        'expiry_date',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:4',
        'purchase_date' => 'date',
        'expiry_date' => 'date'
    ];

    /**
     * Get the ingredient
     */
    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    /**
     * Check if this batch is expired
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date < now()->toDateString();
    }

    /**
     * Check if this batch is near expiry
     */
    public function isNearExpiry(int $days = 3): bool
    {
        return $this->expiry_date && $this->expiry_date <= now()->addDays($days)->toDateString();
    }

    /**
     * Scopes
     */
    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now()->toDateString());
    }

    public function scopeNearExpiry($query, $days = 3)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days)->toDateString());
    }

    public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }

    public function scopeBySupplier($query, $supplier)
    {
        return $query->where('supplier', $supplier);
    }
}
