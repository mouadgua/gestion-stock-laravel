<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChatHistoryController extends Controller
{
    /**
     * Get chat history for the current user
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $messages = ChatMessage::getUserMessages($user->id, 100);

        return response()->json([
            'messages' => $messages,
            'total' => $messages->count(),
        ]);
    }

    /**
     * Store a new chat message
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = $request->user();
        
        $message = ChatMessage::create([
            'user_id' => $user->id,
            'message' => $request->message,
            'sender_type' => 'user',
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * Delete old chat messages
     */
    public function clear(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Keep only last 24 hours of messages
        ChatMessage::where('user_id', $user->id)
            ->where('created_at', '<', now()->subHours(24))
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Anciens messages supprimés',
        ]);
    }
}