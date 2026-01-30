<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WhatsAppSession extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_sessions';

    protected $fillable = [
        'phone',
        'current_state',
        'session_data',
        'last_interaction_at',
    ];

    protected $casts = [
        'session_data' => 'array',
        'last_interaction_at' => 'datetime',
    ];

    /**
     * Get or create a session for a phone number
     */
    public static function getOrCreate(string $phone): self
    {
        return self::firstOrCreate(
            ['phone' => $phone],
            [
                'current_state' => 'main_menu',
                'session_data' => [],
                'last_interaction_at' => now(),
            ]
        );
    }

    /**
     * Update session state
     */
    public function setState(string $state, array $data = []): self
    {
        $this->update([
            'current_state' => $state,
            'session_data' => array_merge($this->session_data ?? [], $data),
            'last_interaction_at' => now(),
        ]);
        
        return $this;
    }

    /**
     * Reset session to main menu
     */
    public function reset(): self
    {
        $this->update([
            'current_state' => 'main_menu',
            'session_data' => [],
            'last_interaction_at' => now(),
        ]);
        
        return $this;
    }

    /**
     * Get a session data value
     */
    public function getData(string $key, $default = null)
    {
        return $this->session_data[$key] ?? $default;
    }

    /**
     * Set a session data value
     */
    public function setData(string $key, $value): self
    {
        $data = $this->session_data ?? [];
        $data[$key] = $value;
        
        $this->update([
            'session_data' => $data,
            'last_interaction_at' => now(),
        ]);
        
        return $this;
    }

    /**
     * Check if session has expired
     */
    public function hasExpired(): bool
    {
        $timeout = config('whatsapp.session_timeout', 30);
        
        if (!$this->last_interaction_at) {
            return true;
        }
        
        return $this->last_interaction_at->diffInMinutes(now()) > $timeout;
    }

    /**
     * Update last interaction timestamp
     */
    public function updateLastInteraction(): bool
    {
        return $this->update(['last_interaction_at' => now()]);
    }
}
