<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use App\Models\Disease;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
 
use App\Http\Resources\DiseaseResource;
use Illuminate\Support\Facades\Validator;

class PlantDiseaseController extends Controller
{
    use Response;
    public function predict(Request $request)
    {
        //  $user = auth('users')->user();


        //  if (!$user || $user->points < 10) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'You do not have enough points to make a prediction.'
        //     ], 400);
        // }
        
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            $client = new Client();
            $response = $client->post('https://99ac-156-197-193-137.ngrok-free.app/predict', [
                'multipart' => [
                    [
                        'name' => 'image',
                        'contents' => fopen($request->file('image')->getRealPath(), 'r'),
                        'filename' => $request->file('image')->getClientOriginalName(),
                        'headers' => [
                            'Content-Type' => $request->file('image')->getMimeType()
                        ]
                    ]
                ]
            ]);

        
            $data = json_decode($response->getBody(), true);

   
            // $prediction = $data['disease'] ?? 'No prediction available';
            // if (isset($data['confidence']) && $data['confidence'] !== null) {
            //     $prediction = sprintf(
            //         '%s (%.1f%%)',
            //         $prediction,
            //         $data['confidence'] * 100
            //     );
            // }

        //     $user->points -= 10;
        //    $user->save();

            return response()->json([
                'success' => true,
                // 'prediction' => $prediction,
                'data' => $data  
            ]);
        } catch (\Exception $e) {
         
            return response()->json([
                'success' => false,
                'message' => 'Failed to process request: ' . $e->getMessage()
            ], 500);
        }
    }


    public function result(Request $request) 
    {
        $name = $request['name'];
    

        $disease = Disease::with(['homeRemedys', 'images', 'symptoms', 'solutions', 'preventions'])->where('name' ,$name)->first();

        if (!$disease) { 
            { 
                return  $this->sendError('Disease not found', 404);
            
            }
        }
        $user = Auth::guard('users')->user();
        $user->points -= 10;
        $user->save();

    
        return $this->sendResponse(new DiseaseResource($disease), 'Disease found successfully', 200);
    }
}
