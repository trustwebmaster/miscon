<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class ProgramSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_date',
        'start_time',
        'end_time',
        'title',
        'description',
        'venue',
        'speaker',
        'category',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    /**
     * Scope for active schedules
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for a specific date
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('event_date', $date);
    }

    /**
     * Scope for today
     */
    public function scopeToday($query)
    {
        return $query->whereDate('event_date', Carbon::today());
    }

    /**
     * Scope for ordering
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('event_date')
                     ->orderBy('start_time')
                     ->orderBy('display_order');
    }

    /**
     * Get formatted time range
     */
    public function getTimeRangeAttribute(): string
    {
        $start = Carbon::parse($this->start_time)->format('H:i');
        
        if ($this->end_time) {
            $end = Carbon::parse($this->end_time)->format('H:i');
            return "{$start} - {$end}";
        }
        
        return $start;
    }

    /**
     * Get category emoji
     */
    public function getCategoryEmojiAttribute(): string
    {
        return match ($this->category) {
            'plenary' => '🎤',
            'workshop' => '📚',
            'worship' => '🙏',
            'break' => '☕',
            'meal' => '🍽️',
            'fellowship' => '🤝',
            default => '📌',
        };
    }

    /**
     * Format for WhatsApp message
     */
    public function toWhatsAppFormat(): string
    {
        $text = "{$this->category_emoji} *{$this->time_range}*\n";
        $text .= "*{$this->title}*\n";
        
        if ($this->venue) {
            $text .= "📍 {$this->venue}\n";
        }
        
        if ($this->speaker) {
            $text .= "👤 {$this->speaker}\n";
        }
        
        if ($this->description) {
            $text .= "\n{$this->description}";
        }
        
        return $text;
    }

    /**
     * Get all schedules grouped by date for WhatsApp
     */
    public static function getFullScheduleForWhatsApp(): string
    {
        $schedules = self::active()->ordered()->get();
        
        if ($schedules->isEmpty()) {
            return "📅 *MISCON26 Program Schedule*\n\nNo schedule available yet. Please check back later.";
        }
        
        $grouped = $schedules->groupBy(fn($s) => $s->event_date->format('Y-m-d'));
        $text = "📅 *MISCON26 FULL PROGRAM SCHEDULE*\n\n";
        
        foreach ($grouped as $date => $daySchedules) {
            $dateFormatted = Carbon::parse($date)->format('l, d F Y');
            $text .= "═══════════════════\n";
            $text .= "📆 *{$dateFormatted}*\n";
            $text .= "═══════════════════\n\n";
            
            foreach ($daySchedules as $schedule) {
                $text .= $schedule->toWhatsAppFormat() . "\n\n";
            }
        }
        
        return $text;
    }

    /**
     * Get today's schedule for WhatsApp
     */
    public static function getTodayScheduleForWhatsApp(): string
    {
        $schedules = self::active()->today()->ordered()->get();
        $today = Carbon::today()->format('l, d F Y');
        
        if ($schedules->isEmpty()) {
            return "📅 *Today's Schedule*\n{$today}\n\nNo events scheduled for today.";
        }
        
        $text = "📅 *TODAY'S SCHEDULE*\n";
        $text .= "📆 {$today}\n";
        $text .= "═══════════════════\n\n";
        
        foreach ($schedules as $schedule) {
            $text .= $schedule->toWhatsAppFormat() . "\n\n";
        }
        
        return $text;
    }
}
