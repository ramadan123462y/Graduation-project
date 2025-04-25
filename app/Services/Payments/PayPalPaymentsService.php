<?php

namespace App\Services\Payments;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\PaymentGatewayInterface;
use App\Services\Payments\BasePaymentService;

class PayPalPaymentsService extends BasePaymentService implements PaymentGatewayInterface
{
    protected $client_id;
    protected $client_secret;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {

        $this->base_url = env("PAYPAL_BASE_URL") ?? "https://api-m.sandbox.paypal.com";
        $this->client_id = env("PAYPAL_CLIENT_ID") ?? "AdUFIvOtjLKi9gyWpvMt8A-LxRqENDM43YBhSu1st6CkrHGlCvVlzjEbVwWo1fa_Kr7WOgMiNMOk0AUZ";
        $this->client_secret = env("PAYPAL_SECRIT_ID") ?? "EGODCXy-rrjA5Qj1iT2vBv53_UEGt-xcr7_sKys2l50HMgHhDxLgymxNiKy7ViYa_zWaTz48mOvPw6zr";

        $this->header = [
            "Accept" => "application/json",
            'Content-Type' => "application/json",
            'Authorization' => "Basic " . base64_encode("$this->client_id:$this->client_secret"),
        ];
    }

    public function sendPayment(Request $request): array
    {
        $data = $this->formatData($request);

        $response = $this->buildRequest("POST", "/v2/checkout/orders", $data);


        if ($response->getData(true)['success']) {

            return ['success' => true, 'url' => $response->getData(true)['data']['links'][1]['href']];
        }
        return ['success' => false, 'url' => route('payment.failed')];
    }



    public function callBack(Request $request): array
    {
        $token = $request->get('token');
    
        $response = $this->buildRequest('POST', "/v2/checkout/orders/$token/capture");
        $responseData = $response->getData(true);
    
        Log::info('Raw PayPal Response:', $responseData);
    
       
         // check if the response structure is valid
        if (!isset($responseData['data']['purchase_units'][0]['payments']['captures'][0])) {
            Log::error('Invalid PayPal response structure', $responseData);
            return [
                'success' => false,
                'user_id' => null,
                'package_id' => null
            ];
        }
    
        //
         // استخراج custom_id بشكل آمن
        $captureData = $responseData['data']['purchase_units'][0]['payments']['captures'][0];
        $customId = $captureData['custom_id'] ?? null;
    
        if (!$customId) {
            Log::error('Custom ID missing in PayPal response', $captureData);
            return [
                'success' => false,
                'user_id' => null,
                'package_id' => null
            ];
        }
    
        $customData = json_decode($customId, true);
    
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Failed to decode custom_id', [
                'custom_id' => $customId,
                'error' => json_last_error_msg()
            ]);
            return [
                'success' => false,
                'user_id' => null,
                'package_id' => null
            ];
        }
    
        return [
            'success' => ($responseData['data']['status'] ?? '') === 'COMPLETED',
            'user_id' => $customData['user_id'] ?? null,
            'package_id' => $customData['package_id'] ?? null
        ];
    }


    public function formatData($request): array
    {
        $userId = auth('users')->id();
        $packageId = $request->package_id;

        $price = $request->price;

        $currency = $request->header('Currency') ?? 'EGP';
        // dd(   $currency);

        
    
        Log::info('Payment Request Data:', [
            'user_id' => $userId,
            'package_id' => $packageId,
            'amount' => $request->price
        ]);
    
        return [
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        // "currency_code" => "USD",
                        // "value" => is_array($request->amount) ? $request->amount['value'] : $request->amount
                        "currency_code" =>  $currency,
                        "value" =>  $price,
                    ],
                    "custom_id" => json_encode([
                        'user_id' => $userId,
                        'package_id' => $packageId
                    ]),
                ]
            ],
            "payment_source" => [
                "paypal" => [
                    "experience_context" => [
                        "return_url" => 'https://9016-41-199-0-28.ngrok-free.app/api/payment/callback',
                        "cancel_url" => route("payment.failed"),
                    ]
                ]
            ]
        ];
    }
}
