<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Core\Trait\FileTrait;
use App\Models\DescreptionArticle;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use App\Core\Dashboard\Service\ArticleService;
use App\Http\Requests\Api\StorePendingArticleRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PendingArticleController extends Controller
{
    use FileTrait;
    public $articleService;
    public function __construct(ArticleService $articleService,)
    {
        $this->articleService = $articleService;
    }
    public function store(StorePendingArticleRequest $request)
    {



        try {
            DB::beginTransaction();

            $mainTitle = $request->main_title;
            $homePage = $request->home_page;
            $mostFamous = $request->most_famous;
            $imageFile = $request->file('image_file');
            $articleSubCategorieId = $request->articlesubcategorie_id;
            $userId = Auth::guard('api')->user()->id;

            $descriptionsTitle = $request->title;
            $descriptionsContent = $request->froala_content;


            $article =  Article::create([
                'title' => $mainTitle,
                'home_page' => $homePage,
                'most_famous' => $mostFamous,
                'image_file' => $imageFile->getClientOriginalName(),
                'articlesubcategorie_id' => $articleSubCategorieId,
                'user_id' => $userId
            ]);


            FileTrait::uploade($imageFile, $imageFile->getClientOriginalName(), 'Articles', 'uploades');

            $this->articleService->createDescriptions(
                $descriptionsTitle,
                $descriptionsContent,
                $article->id

            );

            DB::commit();

            return apiResponse([], 'Article Created SucessFully');
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Server Error',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
