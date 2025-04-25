<?php

namespace App\Http\Controllers\Api;

use App\Models\Plant;
use App\Models\Package;
use App\Models\Section;
use App\Traits\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SearchResource;
use App\Http\Resources\PackageResoucre;
use App\Http\Resources\ProfileResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ShowPlantResource;

class HomeController extends Controller
{
    use Response;
    public function search(){

    // $plants = Plant::with('section')->get();

    $sections = Section::with('plants')->get();

    return $this->sendResponse( SearchResource::collection($sections), 'All Plants' , 200);
    }


    public function show ($id){

        $plant = Plant::with('section')->find($id);

        return $this->sendResponse(new ShowPlantResource($plant), 'Get Plant Success' , 200);
    }

}
