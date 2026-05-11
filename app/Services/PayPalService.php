<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    private string $clientId;
    private string $secret;
    private string $baseUrl;
    private ?string $accessToken = null;

    public function __construct()
    {
        $this->clientId = config('services.paypal.client_id');
        $this->secret = config('services.paypal.secret');
        $this->baseUrl = config('services.paypal.sandbox')
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';
    }

    /**
     * Get OAuth 2.0 access token from PayPal.
     */
    public function getAccessToken(): ?string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        try {
            $response = Http::withBasicAuth($this->clientId, $this->secret)
                ->asForm()
                ->post("{$this->baseUrl}/v1/oauth2/token", [
                    'grant_type' => 'client_credentials',
                ]);

            if ($response->successful()) {
                $this->accessToken = $response->json('access_token');
                return $this->accessToken;
            }

            Log::error('PayPal token error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('PayPal token exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a PayPal order.
     *
     * @param float $total
     * @param string $currency
     * @param string $description
     * @return array|null
     */
    public function createOrder(float $total, string $currency = 'MAD', string $description = 'Commande The Vault'): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        try {
            $response = Http::withToken($token)
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('PayPal-Request-Id', uniqid('vault_', true))
                ->post("{$this->baseUrl}/v2/checkout/orders", [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [
                        [
                            'reference_id' => uniqid('order_', true),
                            'description' => $description,
                            'amount' => [
                                // Remarque: si MAD est passé, il le force en USD,
                                // Mais assure-toi que la valeur a bien été convertie mathématiquement avant !
                                'currency_code' => $currency === 'MAD' ? 'USD' : $currency,
                                'value' => number_format($total, 2, '.', ''),
                            ],
                        ],
                    ],
                    'application_context' => [
                        'brand_name' => 'The Vault',
                        'shipping_preference' => 'NO_SHIPPING',
                        'user_action' => 'PAY_NOW',
                        'return_url' => route('paypal.success'),
                        'cancel_url' => route('paypal.cancel'),
                    ],
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayPal create order error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('PayPal create order exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Capture payment for a PayPal order.
     *
     * @param string $paypalOrderId
     * @return array|null
     */
    public function captureOrder(string $paypalOrderId): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        try {
            $response = Http::withToken($token)
                ->withBody('{}', 'application/json')
                ->post("{$this->baseUrl}/v2/checkout/orders/{$paypalOrderId}/capture");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayPal capture order error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('PayPal capture order exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Refund a PayPal payment.
     *
     * @param string $captureId
     * @param float|null $amount
     * @param string $currency
     * @return array|null
     */
    public function refundPayment(string $captureId, ?float $amount = null, string $currency = 'MAD'): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        try {
            $data = [];
            if ($amount !== null) {
                $data['amount'] = [
                    'value' => number_format($amount, 2, '.', ''),
                    'currency_code' => $currency === 'MAD' ? 'USD' : $currency,
                ];
            }

            $response = Http::withToken($token)
                ->withHeader('Content-Type', 'application/json')
                // Ici aussi on s'assure d'envoyer $data, même si c'est vide (ce que fait déjà ton code, c'est bien)
                ->post("{$this->baseUrl}/v2/payments/captures/{$captureId}/refund", $data);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayPal refund error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('PayPal refund exception: ' . $e->getMessage());
            return null;
        }
    }
}