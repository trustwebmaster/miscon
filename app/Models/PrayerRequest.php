<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrayerRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'name',
        'request',
        'status',
        'admin_notes',
        'prayed_at',
    ];

    protected $casts = [
        'prayed_at' => 'datetime',
    ];

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for prayed requests
     */
    public function scopePrayed($query)
    {
        return $query->where('status', 'prayed');
    }

    /**
     * Mark as prayed
     */
    public function markAsPrayed(?string $notes = null): void
    {
        $this->update([
            'status' => 'prayed',
            'prayed_at' => now(),
            'admin_notes' => $notes,
        ]);
    }

    /**
     * Archive the request
     */
    public function archive(): void
    {
        $this->update(['status' => 'archived']);
    }
}
