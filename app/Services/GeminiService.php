<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private string $apiKey;
    private string $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY', '');
    }

    /**
     * Send a message to Gemini AI and get a response.
     */
    public function chat(string $message, array $context = []): string
    {
        if (empty($this->apiKey)) {
            return "Désolé, la clé API Gemini n'est pas configurée. Veuillez définir GEMINI_API_KEY dans votre fichier .env";
        }

        try {
            $systemPrompt = "Tu es un assistant virtuel pour 'The Vault', une boutique en ligne de produits haut de gamme. 
            Tu aides les clients avec leurs questions sur les produits, les commandes, et le site. 
            Sois poli, concis et utile. Réponds en français.";

            $contents = [
                [
                    'role' => 'user',
                    'parts' => [['text' => $systemPrompt . "\n\n" . $message]]
                ]
            ];

            // Add context from previous messages if available
            if (!empty($context)) {
                $contents = [];
                foreach ($context as $msg) {
                    $contents[] = [
                        'role' => $msg['role'] ?? 'user',
                        'parts' => [['text' => $msg['text'] ?? '']]
                    ];
                }
                $contents[] = [
                    'role' => 'user',
                    'parts' => [['text' => $message]]
                ];
            }

            $response = Http::withHeader('Content-Type', 'application/json')
                ->post("{$this->apiUrl}?key={$this->apiKey}", [
                    'contents' => $contents,
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 500,
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                return $text ?: "Je n'ai pas pu générer une réponse. Veuillez réessayer.";
            }

            Log::error('Gemini API error: ' . $response->body());
            return "Désolé, une erreur est survenue. Veuillez réessayer plus tard.";
        } catch (\Exception $e) {
            Log::error('Gemini API exception: ' . $e->getMessage());
            return "Désolé, une erreur technique est survenue. Veuillez réessayer plus tard.";
        }
    }
}