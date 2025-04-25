<?php

use App\Http\Controllers\Api\ArticleApiController;
use App\Http\Controllers\Api\ArticleCategorieApiController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\PendingArticleController;
use App\Http\Controllers\Api\SettingApiController;
use App\Http\Controllers\Api\SiteMapController;
use App\Http\Controllers\Api\SliderApiController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\SubCategorieApiController;
use App\Http\Controllers\Api\SubCategorieController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VideoApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::prefix('article')->group(function () {
//     Route::get('/find/{articleId}', [ArticleApiController::class, 'find']);
//     Route::get('/related/{articleId}', [ArticleApiController::class, 'related']);
//     Route::get('/writers/{articleId}', [ArticleApiController::class, 'writers']);
//     Route::get('/getByTitle', [ArticleApiController::class, 'getByTitle']);
//     Route::get('/filter-by-categorie-and-subCategorie', [ArticleApiController::class, 'filterByCategorieAndSubCategorie']);


//     Route::get('/home-page', [ArticleApiController::class, 'getArticleHomePage']);


//     Route::prefix('/categorie')->controller(ArticleCategorieApiController::class)->group(function () {
//         Route::get('/all', 'all');

//         Route::get('/subcategories', 'getArticleSubCategories');
//     });
// });

// Route::get('/subcategorie/articles', [SubCategorieApiController::class, 'getArticles']);

// Route::get('/videos/home-page', [VideoApiController::class, 'getHomePage']);


// Route::post('contact/sendMail', [ContactController::class, 'sendMail']);

// Route::get('/get-categories-and-subcategorie', [ArticleCategorieApiController::class, 'getCategoriesAndSubCategorie']);

Route::get('/slider', [SliderController::class, 'get']);

Route::get('/settings', [SettingApiController::class, 'get']);


Route::get('/site-map', [SiteMapController::class, 'generateSitemap']);


Route::get('/categories', [CategorieController::class, 'get']);

Route::get('/sub-categories/get-by-categorie-id', [SubCategorieController::class, 'getByCategorieId']);

Route::get('/get-articles-by-sub-categorie-id', [ArticleController::class, 'getBySubCategorieId']);
Route::get('/get-random', [ArticleController::class, 'getRandom']);

Route::get('/find', [ArticleController::class, 'find']);

Route::prefix('/auth')->controller(AuthController::class)->group(function () {

    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::get('/me', 'me');
    Route::post('/logout', 'logout');
});
Route::middleware('isUser')->group(function () {

    Route::prefix('article/pending')->controller(PendingArticleController::class)->group(function () {


        Route::post('/store', 'store');
    });

    Route::get('user/articles', [UserController::class, 'articles']);
});
