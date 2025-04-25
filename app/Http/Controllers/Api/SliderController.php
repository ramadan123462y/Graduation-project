<?php

namespace App\Http\Controllers\Api;

use App\Models\Slider;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SliderResource;


class SliderController extends Controller
{
    public function get()
    {

        $sliders = Slider::get();

        if ($sliders->isEmpty()) {


            return apiResponse([], "No data found ", 404);
        }

        return apiResponse([
            'sliders' => SliderResource::collection($sliders)
        ], [], 200);
    }
}
