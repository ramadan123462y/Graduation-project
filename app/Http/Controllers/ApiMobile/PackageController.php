<?php

namespace App\Http\Controllers\Api;

use App\Models\Package;
use App\Traits\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\PackageResoucre;

class PackageController extends Controller
{
    use Response;
    // public function packages()
    // {
    //     $packages = Package::all();

    //     return $this->sendResponse(PackageResoucre::collection($packages), 'All Packages', 200);

    // }

    public function packages(Request $request)
    {
        $currency = $request->header('Currency');
    
        $packages = Package::all()->map(function ($package) use ($currency) {
            if ($currency) {
                $package->price = round($this->convertCurrency($package->price, 'EGP', $currency));
            }
            return $package;
        });
    
        return $this->sendResponse(PackageResoucre::collection($packages), 'All Packages', 200);
    }
    
    private function convertCurrency($amount, $from = 'EGP', $to = 'USD') {
        $response = Http::get('https://api.currencylayer.com/convert', [
            'access_key' => '46620d49d78e814e19c7db401f6ca958',
            'from' => $from,
            'to' => $to,
            'amount' => $amount,
        ]);
    
        $conversionResult = json_decode($response->body(), true);
        
        return $conversionResult['result'] ?? $amount;
    }
}
