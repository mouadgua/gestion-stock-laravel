<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiProductController extends Controller
{
    public function describe(Request $request): JsonResponse
    {
        $request->validate([
            'language'    => ['required', 'in:fr,en,ar'],
            'images'      => ['nullable', 'array', 'max:5'],
            'images.*'    => ['string'],
            'product_name'=> ['nullable', 'string', 'max:255'],
        ]);

        $apiKey = env('GEMINI_API_KEY', '');
        if (empty($apiKey)) {
            return response()->json(['error' => 'Clé API Gemini non configurée.'], 500);
        }

        $language = $request->input('language', 'fr');
        $productName = $request->input('product_name', '');
        $imageDataList = $request->input('images', []);

        $languageLabel = match($language) {
            'fr' => 'French (français)',
            'en' => 'English',
            'ar' => 'Arabic (العربية)',
            default => 'French',
        };

        $prompt = "You are a professional e-commerce copywriter for 'The Vault', a premium goods store in Morocco. "
            . "Write a compelling product description in {$languageLabel}. "
            . ($productName ? "The product name is: {$productName}. " : "")
            . "Rules: "
            . "1. Write exactly 3-5 sentences. "
            . "2. Minimum 60 words, maximum 120 words. "
            . "3. Highlight materials, key features, and premium quality. "
            . "4. End with a subtle call-to-action or desirability statement. "
            . "5. Do NOT include the product name as a title or heading. "
            . "6. Do NOT use bullet points, numbered lists, or markdown formatting. "
            . "7. Return plain paragraph text only.";

        $parts = [['text' => $prompt]];

        // Add images as base64 inline data
        foreach ($imageDataList as $imageData) {
            if (str_starts_with($imageData, 'data:')) {
                // base64 data URL from file input
                [$mimeTypePart, $base64Data] = explode(',', $imageData, 2);
                $mimeType = str_replace(['data:', ';base64'], '', $mimeTypePart);
                $parts[] = [
                    'inline_data' => [
                        'mime_type' => $mimeType,
                        'data'      => $base64Data,
                    ],
                ];
            } elseif (str_starts_with($imageData, 'http')) {
                // External URL (Cloudinary)
                $parts[] = [
                    'file_data' => [
                        'mime_type' => 'image/jpeg',
                        'file_uri'  => $imageData,
                    ],
                ];
            }
        }

        $payload = [
            'contents' => [['role' => 'user', 'parts' => $parts]],
            'generationConfig' => [
                'temperature'     => 0.8,
                'maxOutputTokens' => 300,
            ],
        ];

        try {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

            $response = Http::timeout(20)
                ->withHeader('Content-Type', 'application/json')
                ->post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                return response()->json(['description' => trim($text)]);
            }

            $error = $response->json()['error']['message'] ?? $response->body();
            Log::error('AI describe error: ' . $error);
            return response()->json(['error' => 'Erreur API: ' . $error], 500);

        } catch (\Exception $e) {
            Log::error('AI describe exception: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur technique: ' . $e->getMessage()], 500);
        }
    }
}
