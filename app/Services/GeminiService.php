<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private string $apiKey;
    private string $model = 'gemini-2.5-flash';
    private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY', '');
    }

    public function chat(string $message, array $context = []): string
    {
        if (empty($this->apiKey)) {
            return "Désolé, la clé API Gemini n'est pas configurée. Veuillez définir GEMINI_API_KEY dans votre fichier .env";
        }

        try {
            $contents = [];

            // Rebuild conversation history from context
            foreach ($context as $msg) {
                $role = $msg['role'] ?? 'user';
                // Gemini only accepts 'user' and 'model' roles
                if (!in_array($role, ['user', 'model'])) {
                    $role = 'user';
                }
                $contents[] = [
                    'role' => $role,
                    'parts' => [['text' => $msg['text'] ?? '']],
                ];
            }

            // Add current user message
            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => $message]],
            ];

            $payload = [
                'system_instruction' => [
                    'parts' => [[
                        'text' => "Tu es un assistant virtuel pour 'The Vault', une boutique en ligne de produits haut de gamme basée au Maroc. "
                            . "Tu aides les clients avec leurs questions sur les produits, les commandes, la livraison et le site. "
                            . "Sois poli, concis et utile. Réponds toujours en français. "
                            . "Les prix sont en Dirhams (DH). La devise est le Dirham marocain (MAD)."
                    ]],
                ],
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 512,
                    'topP' => 0.95,
                ],
                'safetySettings' => [
                    ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ],
            ];

            $url = "{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}";

            $response = Http::timeout(15)
                ->withHeader('Content-Type', 'application/json')
                ->post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                return $text ?: "Je n'ai pas pu générer une réponse. Veuillez réessayer.";
            }

            $errorBody = $response->json();
            $errorMsg = $errorBody['error']['message'] ?? $response->body();
            Log::error('Gemini API error: ' . $errorMsg);

            if ($response->status() === 400) {
                return "Désolé, le message n'a pas pu être traité. Essayez de reformuler.";
            }
            if ($response->status() === 403) {
                return "Clé API Gemini invalide ou accès refusé. Vérifiez GEMINI_API_KEY dans .env.";
            }

            return "Désolé, une erreur est survenue. Veuillez réessayer plus tard.";

        } catch (\Exception $e) {
            Log::error('Gemini API exception: ' . $e->getMessage());
            return "Désolé, une erreur technique est survenue. Veuillez réessayer plus tard.";
        }
    }
}
