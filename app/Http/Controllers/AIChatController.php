<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AIChatController extends Controller
{
    protected GeminiService $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    /**
     * Send a message to the AI assistant.
     */
    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $message = $request->input('message');
        $context = $request->input('context', []);

        $response = $this->gemini->chat($message, $context);

        return response()->json([
            'success' => true,
            'response' => $response,
        ]);
    }
}