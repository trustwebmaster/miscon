<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Registration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'reference',
        'type',
        'full_name',
        'university',
        'phone',
        'id_number',
        'gender',
        'level',
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
     * Registration amounts
     */
    const STUDENT_AMOUNT = 45.00;
    const ALUMNI_AMOUNT = 65.00;

    /**
     * Generate a unique reference number
     */
    public static function generateReference(): string
    {
        do {
            $reference = 'MISCON26-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        } while (self::where('reference', $reference)->exists());

        return $reference;
    }

    /**
     * Get the amount based on registration type
     */
    public static function getAmount(string $type): float
    {
        return $type === 'student' ? self::STUDENT_AMOUNT : self::ALUMNI_AMOUNT;
    }

    /**
     * Scope for students
     */
    public function scopeStudents($query)
    {
        return $query->where('type', 'student');
    }

    /**
     * Scope for alumni
     */
    public function scopeAlumni($query)
    {
        return $query->where('type', 'alumni');
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
     * Get formatted ID label based on type
     */
    public function getIdLabel(): string
    {
        return $this->type === 'student' ? 'Reg Number' : 'National ID';
    }

    /**
     * Get formatted level label based on type
     */
    public function getLevelLabel(): string
    {
        return $this->type === 'student' ? 'Level' : 'Graduation Year';
    }
}
