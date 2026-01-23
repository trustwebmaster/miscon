<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GuestSpeaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'topic',
        'bio',
        'image_url',
        'organization',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope for active speakers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name');
    }

    /**
     * Get full display name (with title)
     */
    public function getFullNameAttribute(): string
    {
        return $this->title ? "{$this->title} {$this->name}" : $this->name;
    }

    /**
     * Format for WhatsApp message
     */
    public function toWhatsAppFormat(): string
    {
        $text = "👤 *{$this->full_name}*\n";
        
        if ($this->organization) {
            $text .= "🏛️ {$this->organization}\n";
        }
        
        if ($this->topic) {
            $text .= "📖 Topic: {$this->topic}\n";
        }
        
        if ($this->bio) {
            $text .= "\n{$this->bio}";
        }
        
        return $text;
    }
}
