<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'reference',
        'donor_name',
        'donor_email',
        'donor_phone',
        'message',
        'amount',
        'payment_status',
        'payment_method',
        'payment_phone',
        'paynow_reference',
        'paynow_poll_url',
        'paid_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Minimum donation amount (USD)
     */
    const MIN_AMOUNT = 1.00;

    /**
     * Generate a unique reference number
     */
    public static function generateReference(): string
    {
        do {
            $reference = 'DON-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        } while (self::where('reference', $reference)->exists());

        return $reference;
    }

    /**
     * Scope for completed payments
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'completed');
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Check if payment is completed
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'completed';
    }

    /**
     * Mark payment as completed
     */
    public function markAsPaid(?string $paynowReference = null): void
    {
        $this->update([
            'payment_status' => 'completed',
            'paynow_reference' => $paynowReference,
            'paid_at' => now(),
        ]);
    }

    /**
     * Get display name (Anonymous if not provided)
     */
    public function getDisplayName(): string
    {
        return $this->donor_name ?: 'Anonymous';
    }

    /**
     * Calculate how many students this donation can sponsor
     */
    public function getStudentsSponsored(): int
    {
        return (int) floor($this->amount / 45);
    }
}
