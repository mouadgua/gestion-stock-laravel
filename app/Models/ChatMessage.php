<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'session_id',
        'sender_type',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get messages for a user
     */
    public static function getUserMessages(int $userId, int $limit = 50)
    {
        return static::where('user_id', $userId)
            ->orWhere('sender_type', 'system')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values();
    }

    /**
     * Create a system message
     */
    public static function createSystemMessage(string $message, string $sessionId = null)
    {
        return static::create([
            'message' => $message,
            'session_id' => $sessionId,
            'sender_type' => 'system',
        ]);
    }

    /**
     * Scope to filter by session
     */
    public function scopeBySession($query, string $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Scope to get only user messages
     */
    public function scopeUserOnly($query)
    {
        return $query->where('sender_type', 'user');
    }

    /**
     * Scope to get only system messages
     */
    public function scopeSystemOnly($query)
    {
        return $query->where('sender_type', 'system');
    }
}