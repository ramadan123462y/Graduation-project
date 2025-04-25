<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SubCategorieResource;
use App\Models\Articlecategorie;
use App\Models\Articlesubcategorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class SubCategorieController extends Controller
{
    public function getByCategorieId(Request $request)
    {

        $validator = Validator::make(
            ['categorieId' => $request->categorieId],
            ['categorieId' => 'required|exists:articlecategories,id'],
            [
                'categorieId.required' => "Categorie Id Required",
                'categorieId.exists' => "This Id In Not Found In Categories"
            ]
        );
        if ($validator->fails()) {

            return apiResponse([], $validator->errors(), 422);
        }

        $categorieId = $request->categorieId;

        $categorie = Articlecategorie::find($categorieId);

        $subCategories = $categorie->articlesubcategories()->withCount('articles')->withSum('articles', 'count_views')->get();

        $articles = $categorie->articlesubcategories()->with('articles')->get()->pluck('articles')->flatten();

        $statics = [
            'categorieName' =>  $categorie->name,
            'categorieUrlImage' => URL::asset("Backend/Uploades/Articles/Categories/$categorie->image_name"),
            'diseaseCount' =>  $categorie->articlesubcategories()->count(),
            'articleCount' => $articles->count(),
            'articleReadsCount' => $articles->sum('count_views')
        ];

        return apiResponse([
            'statics' => $statics,
            'subCategories' => SubCategorieResource::collection($subCategories),
        ], "sucess", 200);
    }
}
