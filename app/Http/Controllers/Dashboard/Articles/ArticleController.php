<?php

namespace App\Http\Controllers\Dashboard\Articles;

use App\Core\Dashboard\MessageConstants;
use App\Core\Dashboard\Repository\Article\ArticleRepository;
use App\Core\Dashboard\Repository\ArticleSubCategorie\ArticleSubCategorieRepository;
use App\Core\Dashboard\Repository\User\UserRepository;
use App\Core\Dashboard\Service\ArticleService;
use App\Core\Trait\FileTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{



    use FileTrait;
    public $articleService;
    public $articleRepository;
    public $userRepository;
    public $articleSubCategorieRepository;

    public function __construct(
        ArticleService $articleService,
        ArticleRepository $articleRepository,
        UserRepository $userRepository,
        ArticleSubCategorieRepository $articleSubCategorieRepository
    ) {
        $this->articleService = $articleService;
        $this->articleRepository = $articleRepository;
        $this->userRepository = $userRepository;
        $this->articleSubCategorieRepository = $articleSubCategorieRepository;
    }


    public function index()
    {

        $articles = Article::with([
            'articlesubcategorie',
            'user',
            'descreptionArticles',
            'subUsers',
            'banner',
            'galleries',
            'video',
            'beforeafter'
        ])->paginate(5);

        $subCategories = $this->articleSubCategorieRepository->getIdAndName();
        return view('Dashboard.pages.Articles.Article.index', compact('subCategories', 'articles'));
    }

    public function store(ArticleRequest $request)
    {





        try {
            DB::beginTransaction();

            validator($request->all(), [

                'image_file' => 'required|image'
            ])->validate();
            // Prepare Date
            $title = $request->main_title;
            $mostFamous = $request->most_famous;
            $homePage = $request->home_page;
            $status = $request->status;
            $articleSubcategorieId = $request->articlesubcategorie_id;
            $adminId = Auth::guard('admin')->user()->id;
            $imageFile = $request->file('image_file');

            // return $imageFile->getClientOriginalName();
            // Store Article
            $article = Article::create([
                'title' => $title,
                'home_page' => $homePage,
                'most_famous' => $mostFamous,
                'image_file' => $imageFile->getClientOriginalName(),
                'articlesubcategorie_id' => $articleSubcategorieId,
                'status' => $status,
                'admin_id' => $adminId
            ]);

            FileTrait::uploade($imageFile, $imageFile->getClientOriginalName(), 'Articles', 'uploades');

            $this->articleService->createDescriptions(
                $request->title,
                $request->froala_content,
                $article->id

            );


            DB::commit();
            notyf()
                ->position('x', 'center')
                ->position('y', 'top')
                ->success(MessageConstants::ARTICLE_CREATED);


            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(MessageConstants::ERROR_OCCURRED . $e->getMessage());
        }
    }



    public function edit($id)
    {

        $article = Article::with([
            'articlesubcategorie',
            'user',
            'descreptionArticles',
            'subUsers',
            'banner',
            'galleries',
            'video',
            'beforeafter'
        ])->findOrFail($id);

        $subCategories = $this->articleSubCategorieRepository->getIdAndName();
        return view('Dashboard.pages.Articles.Article.edit', compact(
            'subCategories',

            'article'

        ));
    }

    public function update(ArticleRequest $request)
    {

        try {
            DB::beginTransaction();

            $articleId = $request->article_id;
            $title = $request->main_title;
            $mostFamous = $request->most_famous;
            $homePage = $request->home_page;
            $status = $request->status;
            $articleSubcategorieId = $request->articlesubcategorie_id;

            $article = Article::findOrFail($articleId);
            $article->update([
                'title' => $title,
                'home_page' => $homePage,
                'most_famous' => $mostFamous,
                'articlesubcategorie_id' => $articleSubcategorieId,
                'status' => $status,
            ]);

            if ($request->hasFile('image_file')) {
                validator($request->all(), [

                    'image_file' => 'required|image'
                ])->validate();

                $imageFile = $request->file('image_file');

                FileTrait::uploade($imageFile, $imageFile->getClientOriginalName(), 'Articles', 'uploades');

                $article->update([
                    'image_file' => $imageFile->getClientOriginalName(),
                ]);
            }



            $article->descreptionArticles()->delete();

            $this->articleService->createDescriptions(
                $request->title,
                $request->froala_content,
                $article->id

            );

            DB::commit();
            notyf()
                ->position('x', 'center')
                ->position('y', 'top')
                ->success(MessageConstants::ARTICLE_UPDATED);

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(MessageConstants::ERROR_OCCURRED . $e->getMessage());
        }
    }



    public function delete($id)
    {


        try {
            DB::beginTransaction();

            $this->articleService->delete($id);

            DB::commit();


            notyf()
                ->position('x', 'center')
                ->position('y', 'top')
                ->success(MessageConstants::ARTICLE_DELETED);
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(MessageConstants::ERROR_OCCURRED . $e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        $status = $request->status;
        $articleId = $request->articleId;

        $article = Article::findOrFail($articleId);
        $article->update(['status' => $status]);

        return response()->json([
            'success' => true,
            'message' => "Status Updated Successfully"
        ]);
    }
}
