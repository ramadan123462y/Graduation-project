<?php

namespace App\Services\Payments;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\PaymentGatewayInterface;

class MyFatoorahPaymentService extends BasePaymentService implements PaymentGatewayInterface
{
    protected $api_key;

    public function __construct()
    {
        $this->base_url = env("MYFATOORAH_BASE_URL") ?? "https://apitest.myfatoorah.com";
        $this->api_key = env("MYFATOORAH_API_KEY") ?? "rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL";
        $this->header = [
            'accept' => 'application/json',
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $this->api_key,
        ];
    }

    public function sendPayment(Request $request): array
    {
        try {
            $currency = $request->header('Currency', 'EGP'); 
            $price = $request->price;
            $userId = auth('users')->id();
            $user = auth('users')->user(); 
    
            if (!$user) {
                \Log::error('User not authenticated');
                return ['success' => false, 'url' => route('payment.failed')];
            }
    
            $package = Package::find($request->package_id);
            if (!$package) {
                \Log::error('Package not found', ['package_id' => $request->package_id]);
                return ['success' => false, 'url' => route('payment.failed')];
            }
    
            $data = [
                'InvoiceValue' => $price,
                'DisplayCurrencyIso' => $currency, 
                'CustomerName' => $user->user_name,
                'CustomerEmail' => $user->email, 
                'CustomerReference' => json_encode([
                    'user_id' => $userId,
                    'package_id' => $request->package_id
                ]),
                'NotificationOption' => "LNK",
                'Language' => "en",
                'CallBackUrl' => route('payment.myfatoorah.callback'),
                'ErrorUrl' => route('payment.failed'),
            ];
    
            \Log::info('MyFatoorah Payment Request:', $data);
    
            $response = $this->buildRequest('POST', '/v2/SendPayment', $data);
            $responseData = $response->getData(true);
    
            \Log::info('MyFatoorah Full Response:', $responseData);

    
            if ($responseData['data']['IsSuccess'] ?? false) {
                return [
                    'success' => true,
                    'url' => $responseData['data']['Data']['InvoiceURL']
                ];
            }
    
            return ['success' => false, 'url' => route('payment.failed')];
    
        } catch (\Exception $e) {
            \Log::error('MyFatoorah Error: ' . $e->getMessage());
            return ['success' => false, 'url' => route('payment.failed')];
        }
    }

    public function callBack(Request $request): array
    {
        $data = [
            'KeyType' => 'paymentId',
            'Key' => $request->input('paymentId'),
        ];

        $response = $this->buildRequest('POST', '/v2/getPaymentStatus', $data);
        $responseData = $response->getData(true);

        Storage::put('myfatoorah_response.json', json_encode($responseData));

        $customData = json_decode(
            $responseData['data']['Data']['CustomerReference'] ?? '',
            true
        );

        return [
            'success' => $responseData['data']['Data']['InvoiceStatus'] === 'Paid',
        'user_id' => $customData['user_id'] ?? null,
        'package_id' => $customData['package_id'] ?? null,
        'currency' => $responseData['data']['Data']['Currency'] ?? 'USD', 
        'price' => $responseData['data']['Data']['InvoiceValue'] ?? 0 
        ];
    }
}