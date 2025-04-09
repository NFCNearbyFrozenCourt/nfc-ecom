<?php

namespace App\Utils;

use App\Business;

class CashfreeUtil
{
    /**
     * Get CashFree payment gateway configuration details
     *
     * @param int $business_id
     * @return array|null
     */
    public static function getCashfreeDetails($business_id)
    {
        // Get business and POS settings
        $business = Business::find($business_id);
        
        if (!$business) {
            return null;
        }
        
        $pos_settings = json_decode($business->pos_settings, true);
        
        // Check if CashFree settings exist
        if (empty($pos_settings) || 
            !isset($pos_settings['cashfree_api_key']) || 
            !isset($pos_settings['cashfree_secret_key'])) {
            return null;
        }
        
        // Prepare settings array
        $settings = [
            'api_key' => $pos_settings['cashfree_api_key'],
            'secret_key' => $pos_settings['cashfree_secret_key'],
            'environment' => $pos_settings['cashfree_environment'] ?? 'test',
            'cashfree_account_id' => $pos_settings['cashfree_account_id'],
            'endpoint' => ($pos_settings['cashfree_environment'] ?? 'test') == 'test' 
                ? 'https://sandbox.cashfree.com/pg/orders'
                : 'https://api.cashfree.com/pg/orders',
        ];
        
        return $settings;
    }
    
    /**
     * Create payment session with CashFree
     *
     * @param array $cashfree_settings
     * @param float $amount
     * @param array $customer
     * @param string $order_id
     * @param string $return_url
     * @param string $notify_url
     * @return object|null
     */
    public static function createPaymentSession($cashfree_settings, $amount, $customer, $order_id, $return_url, $notify_url, $transaction)
    {
        try {
            $requestData = [
                'order_id' => $order_id,
                'order_amount' => number_format($amount, 2, '.', ''),
                'order_currency' => 'INR',
                'customer_details' => [
                    'customer_id' => (string) $transaction->id,
                    'customer_name' => $customer['name'],
                    'customer_email' => $customer['email'] ?? '',
                    'customer_phone' => $customer['phone'] ?? '9876543210',
                ],
                'order_meta' => [
                    'notifyUrl' => $notify_url,
                    'return_url' => $return_url,
                ]
            ];
            
            $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                ->withHeaders([
                    'X-Client-Secret' => $cashfree_settings['secret_key'],
                    'X-Client-Id' => $cashfree_settings['api_key'],
                    'x-api-version' => '2023-08-01',
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($cashfree_settings['endpoint'], $requestData);
                
            if (!$response->successful()) {
                \Log::error('Cashfree API Error', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                    'request' => $requestData
                ]);
                return null;
            }
            
            return (object) [
                'success' => true,
                'data' => $response->object(),
                'request' => $requestData
            ];
            
        } catch (\Exception $e) {
            \Log::error('Cashfree Payment Session Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return (object) [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}