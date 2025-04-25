<?php

namespace App\Observers;

use App\Models\Article;
use App\Core\Dashboard\Service\SiteMapService;

class ArticleObserve
{


    public $siteMapService;

    public function __construct(SiteMapService $siteMapService)
    {
        $this->siteMapService = $siteMapService;
    }

    /**
     * Handle the Article "created" event.
     */
    public function created(Article $article): void
    {
        $this->siteMapService->generation();
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        $this->siteMapService->generation();
    }

    /**
     * Handle the Article "restored" event.
     */
    public function restored(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "force deleted" event.
     */
    public function forceDeleted(Article $article): void
    {
        //
    }
}
