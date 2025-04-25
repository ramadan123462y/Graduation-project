<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class PendingArticleController extends Controller
{
    public function index()
    {

        $articles = Article::whereHas('user')->InActive()
            ->with([
                'articlesubcategorie',
                'user',
                'descreptionArticles',
                'subUsers',
                'banner',
                'galleries',
                'video',
                'beforeafter'
            ])->paginate(5);
        return view('Dashboard.pages.Articles.PendingArticles.index', compact('articles'));
    }
}
