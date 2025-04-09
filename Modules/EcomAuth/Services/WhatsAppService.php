<?php

namespace Modules\EcomAuth\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiUrl;
    protected $phoneNumberId;
    protected $accessToken;
    protected $appId;
    protected $appSecret;
    
    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url', env('WHATSAPP_API_URL'));
        $this->phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID'); // Default phone number ID
        $this->accessToken = env('WHATSAPP_ACCESS_TOKEN');
        $this->appId = env('FACEBOOK_APP_ID'); // Add to .env
        $this->appSecret = env('FACEBOOK_APP_SECRET'); // Add to .env
        
        // Check if token needs refreshing
        $this->checkAndRefreshToken();
    }
    
    /**
     * Send message via WhatsApp Business API with detailed response
     * 
     * @param string $to Recipient's phone number in E.164 format
     * @param string $message Message content
     * @return array Response with success status and error details if any
     */
    public function sendMessageWithResponse(string $to, string $message): array
    {

        $this->checkAndRefreshToken();
        
        try {
            // Get the phone number ID (or use the default one)
            $phoneNumberId = $this->phoneNumberId;
            if (empty($phoneNumberId)) {
                $phoneNumberId = $this->getPhoneNumberId();
                if (empty($phoneNumberId)) {
                    return [
                        'success' => false,
                        'error' => 'Could not determine WhatsApp Phone Number ID',
                        'exception' => true
                    ];
                }
            }
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json'
            ])->post("{$this->apiUrl}/{$phoneNumberId}", [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'preview_url' => false,
                    'body' => $message
                ]
            ]);
            
            print_r( $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json'
            ])->post("{$this->apiUrl}/{$phoneNumberId}", [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'preview_url' => false,
                    'body' => $message
                ]
            ]));

            if ($response->successful()) {
                Log::info("WhatsApp message sent successfully", [
                    'to' => $to,
                    'response' => $response->json()
                ]);
                return [
                    'success' => true,
                    'response' => $response->json()
                ];
            } else {
                $errorData = $response->json();
                Log::error("Failed to send WhatsApp message", [
                    'to' => $to,
                    'status' => $response->status(),
                    'response' => $errorData
                ]);
                return [
                    'success' => false,
                    'error' => $errorData['error']['message'] ?? 'Unknown API error',
                    'error_code' => $errorData['error']['code'] ?? null,
                    'http_status' => $response->status(),
                    'full_response' => $errorData
                ];
            }
        } catch (\Exception $e) {
            Log::error("Exception when sending WhatsApp message", [
                'to' => $to,
                'exception' => $e->getMessage()
            ]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'exception' => true
            ];
        }
    }
    
    /**
     * Refresh the Facebook access token
     * 
     * @return void
     */
    public function refreshToken()
    {
        try {
            $url = "https://graph.facebook.com/oauth/access_token";
            $response = Http::get($url, [
                'grant_type' => 'fb_exchange_token',
                'client_id' => $this->appId,
                'client_secret' => $this->appSecret,
                'fb_exchange_token' => $this->accessToken,
            ]);

            if ($response->successful()) {
                $newToken = $response->json()['access_token'];
                $this->updateEnvFile($newToken);
                Log::info("Facebook Access Token Refreshed Successfully.");
                $this->accessToken = $newToken; // Update the token in the current instance
            } else {
                Log::error("Failed to refresh Facebook Access Token.", $response->json());
            }

        } catch (\Exception $e) {
            Log::error("Error refreshing Facebook Access Token: " . $e->getMessage());
        }
    }

    /**
     * Update the .env file with the new token
     * 
     * @param string $newToken The new access token
     * @return void
     */
    private function updateEnvFile($newToken)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            $envContents = file_get_contents($path);
            $envContents = preg_replace("/WHATSAPP_ACCESS_TOKEN=.*/", "WHATSAPP_ACCESS_TOKEN={$newToken}", $envContents);
            file_put_contents($path, $envContents);

            // Reload the .env file
            app()->environmentFilePath();
            putenv("WHATSAPP_ACCESS_TOKEN={$newToken}");
        }
    }

    /**
     * Check if token needs refreshing and refresh if needed
     * 
     * @return void
     */
    private function checkAndRefreshToken()
    {
        $lastUpdated = storage_path('app/last_token_refresh.txt');

        if (!file_exists(filename: $lastUpdated) || time() - filemtime($lastUpdated) > (55 * 24 * 60 * 60)) {
            $this->refreshToken();
            file_put_contents($lastUpdated, now());
        }
    }

    /**
     * Fetch the Phone Number ID associated with the WhatsApp Business Account
     * 
     * @return string|null The Phone Number ID or null if not found
     */
    public function getPhoneNumberId()
    {
        $whatsappBusinessAccountId = env('WHATSAPP_BUSINESS_ACCOUNT_ID'); // Add to .env
        $url = "https://graph.facebook.com/v14.0/{$whatsappBusinessAccountId}/phone_numbers?access_token={$this->accessToken}";

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data'][0]['id'])) {
                    return $data['data'][0]['id']; // Return the Phone Number ID
                }
                
                Log::error("Phone Number ID not found in the response.", $data);
                return null;
            }

            Log::error("Failed to fetch Phone Number ID.", $response->json());
            return null;

        } catch (\Exception $e) {
            Log::error("Error fetching Phone Number ID: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Legacy method for backward compatibility
     * 
     * @param string $to Recipient's phone number
     * @param string $message Message content
     * @return bool Success status
     */
    public function sendMessage(string $to, string $message): bool
    {
        $this->checkAndRefreshToken();
        $result = $this->sendMessageWithResponse($to, $message);
        return $result['success'];
    }


    public function sendOtpTemplate(string $to, string $otpCode): array
    {
        $this->checkAndRefreshToken();
    
        try {
            $phoneNumberId = $this->phoneNumberId;
    
            $payload = [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $to,
                'type' => 'template',
                'template' => [
                    'name' => 'otp_verficarion',
                    'language' => [
                        'code' => 'en_US'
                    ],
                    'components' => [
                        [
                            'type' => 'body',
                            'parameters' => [
                                [
                                    'type' => 'text',
                                    'text' => $otpCode
                                ]
                            ]
                        ],
                        [
                            'type' => 'button',
                            "sub_type"=> "url",
                            'index' => '0',
                            'parameters' => [
                                [
                                    'type' => 'text',
                                    'text' => $otpCode
                                ]
                            ]
                        ]
                    ]
                ]
            ];
    
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json'
            ])->post("{$this->apiUrl}/{$phoneNumberId}/messages", $payload);
    
            if ($response->successful()) {
                Log::info("WhatsApp OTP template sent successfully", [
                    'to' => $to,
                    'response' => $response->json()
                ]);
                return [
                    'success' => true,
                    'response' => $response->json()
                ];
            } else {
                $errorData = $response->json();
                Log::error("Failed to send WhatsApp OTP template", [
                    'to' => $to,
                    'status' => $response->status(),
                    'response' => $errorData
                ]);
                return [
                    'success' => false,
                    'error' => $errorData['error']['message'] ?? 'Unknown API error',
                    'error_code' => $errorData['error']['code'] ?? null,
                    'http_status' => $response->status(),
                    'full_response' => $errorData
                ];
            }
        } catch (\Exception $e) {
            Log::error("Exception when sending WhatsApp OTP template", [
                'to' => $to,
                'exception' => $e->getMessage()
            ]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'exception' => true
            ];
        }
    }
}