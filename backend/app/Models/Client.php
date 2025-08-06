<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'origin',
        'document',
        'birth_date',
        'gender',
        'preferences',
        'notes',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'preferences' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the notes for the client.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Scope for active clients
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Search scope
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'ILIKE', "%{$term}%")
                    ->orWhere('email', 'ILIKE', "%{$term}%")
                    ->orWhere('phone', 'ILIKE', "%{$term}%");
    }

    /**
     * Get total spent by client
     */
    public function getTotalSpentAttribute()
    {
        return $this->notes()->where('status', 'paid')->sum('total_amount');
    }

    /**
     * Get pending amount
     */
    public function getPendingAmountAttribute()
    {
        return $this->notes()->whereIn('status', ['confirmed'])->sum('total_amount') - 
               $this->notes()->whereIn('status', ['confirmed'])->sum('total_paid');
    }
}