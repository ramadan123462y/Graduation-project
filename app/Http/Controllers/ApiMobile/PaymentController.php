<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Package;
use App\Traits\Response;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Payments\PayPalPaymentsService;
use App\Services\Payments\MyFatoorahPaymentService;

class PaymentController extends Controller
{
    use Response;

    protected PayPalPaymentsService $payPalPaymentsService;
    protected MyFatoorahPaymentService $MyFatoorahPaymentService;


    public function __construct(PayPalPaymentsService $payPalPaymentsService , MyFatoorahPaymentService $MyFatoorahPaymentService) 
    {
        $this->payPalPaymentsService = $payPalPaymentsService;
        $this->MyFatoorahPaymentService = $MyFatoorahPaymentService;
    }


    public function paymentProcess(Request $request)
    {
        $package = Package::find($request->package_id);
        
        if (!$package) {
            return $this->sendError('Package not found', []);  
        }
    
        // $request->merge(['amount' => $package->price]);
        
        return $this->payPalPaymentsService->sendPayment($request);
    }

    public function callBack(Request $request)
    {
        try {
            $response = $this->payPalPaymentsService->callBack($request);
    
            Log::info('Callback Processing:', $response);
    
            if ($response['success'] && $response['user_id'] && $response['package_id']) {
                $user = User::find($response['user_id']);
                $package = Package::find($response['package_id']);
    
                if (!$user || !$package) {
                    throw new \Exception("User or Package not found");
                }
    
                DB::transaction(function () use ($user, $package) {

                    $user->increment('points', $package->coins);
                    
                    Transaction::create([
                        'user_id' => $user->id,
                        'package_id' => $package->id,
                        'gateway' => 'paypal',
                        'amount' => $package->price,
                        'coins' => $package->coins,
                        'status' => 'success',
                    ]);
                });
    
                return redirect()->route('payment.success');
            }
    
            throw new \Exception("Payment failed or missing data");
        } catch (\Exception $e) {
            Log::error('Payment Callback Error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
    
            Transaction::create([
                'user_id' => $response['user_id'] ?? null,
                'package_id' => $response['package_id'] ?? null,
                'gateway' => 'paypal',
                'amount' => 0,
                'coins' => 0,
                'status' => 'failed',
            ]);
    
            return redirect()->route('payment.failed');
        }
    }


    public function processMyFatoorah(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id'
        ]);
    
        $response = $this->MyFatoorahPaymentService->sendPayment($request);

    if ($response['success']) {
        return response()->json([
            'success' => true,
            'url' => $response['url']
        ]);
    }

    return response()->json([
        'success' => false,
        'error' => 'Payment failed'
    ]);
    }



    public function handleMyFatoorahCallback(Request $request)
{
    $response = $this->MyFatoorahPaymentService->callBack($request);

    if ($response['success'] && $response['user_id'] && $response['package_id']) {
        $user = User::find($response['user_id']);
        $package = Package::find($response['package_id']);

        if ($user && $package) {
            $user->increment('points', $package->coins);
            Transaction::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'gateway' => 'myfatoorah',
                'amount' => $package->price,
                'coins' => $package->coins,
                'currency' => $response['currency'], 

                'status' => 'success',
            ]);
            return redirect()->route('payment.success');
        }
    }

    Transaction::create([
        'user_id' => $response['user_id'],
        'package_id' => $response['package_id'],
        'gateway' => 'myfatoorah',
        'amount' => 0,
        'coins' => 0,
        'status' => 'failed',
    ]);

    return redirect()->route('payment.failed');
}

    public function success()
    {
        

        return view('payment-success');
    }


    public function failed()  
    {
        return view('payment-failed');
    }
}
