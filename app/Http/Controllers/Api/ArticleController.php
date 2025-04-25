<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\Api\ArticleDetailsResource;
use App\Models\Articlesubcategorie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ArticleResource;
use App\Models\Article;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{


    public function getBySubCategorieId(Request $request)
    {

        $validator = Validator::make(
            ['subCategorieId' => $request->subCategorieId],
            ['subCategorieId' => 'required|exists:articlesubcategories,id|integer'],
        );

        if ($validator->fails()) {

            return apiResponse([], $validator->messages(), 404);
        }

        $subCategorieId = $request->subCategorieId;

        $subcategory =  Articlesubcategorie::find($subCategorieId);

        $articlesInSubcategory = $subcategory->articles()->active()->with('descreptionArticles')->get();

        return apiResponse([

            'articles' =>  ArticleResource::collection($articlesInSubcategory)
        ], [], 200);
    }


    public function find(Request $request)
    {
        $validator = Validator::make(
            ['articleId' => $request->articleId],
            ['articleId' => 'required|exists:articles,id|integer'],
        );

        if ($validator->fails()) {

            return apiResponse([], $validator->messages(), 404);
        }

        $articleId = $request->articleId;

        $article = Article::with(['descreptionArticles' => function ($query) {

            $query->select(['id', 'article_id', 'title', 'content']);
        }])->find($articleId);

        return apiResponse([
            'article' => new ArticleDetailsResource($article)
        ], [], 200);
    }

    public function getRandom()
    {

        $articles = Article::active()->where('home_page', true)->get();

        if ($articles->isEmpty()) {
            return apiResponse([], "Data Not Found ", 404);
        }

        return apiResponse([

            'articles' =>  ArticleResource::collection($articles)
        ], [], 200);
    }
}
