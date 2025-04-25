<?php


namespace App\Core\Dashboard\Strategy\ArticleAttachments;

use App\Core\Dashboard\Service\ArticleService;

class ArticleBannerStrategy implements ArticleAttachmentsInterface
{

    public $articleService;


    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function save($request, $article)
    {

        $this->articleService->handleBannerOption($request, $article);
    }
}
