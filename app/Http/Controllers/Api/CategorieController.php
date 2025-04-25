<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CategorieResource;
use App\Models\Articlecategorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{


    public function get()
    {

        if (!Articlecategorie::exists()) {


            return apiResponse([], "Data Not Found ",404);
        }
        $categories = CategorieResource::collection(Articlecategorie::get());

        return apiResponse(['categories' => $categories]);
    }
}
