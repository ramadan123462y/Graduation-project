<?php

namespace App\Observers;

use App\Models\Articlesubcategorie;
use App\Core\Dashboard\Service\SiteMapService;

class ArticleSubCategorieObserve
{

    public $siteMapService;

    public function __construct(SiteMapService $siteMapService)
    {
        $this->siteMapService = $siteMapService;
    }

    /**
     * Handle the Articlesubcategorie "created" event.
     */
    public function created(Articlesubcategorie $articlesubcategorie): void
    {
        $this->siteMapService->generation();
    }

    /**
     * Handle the Articlesubcategorie "updated" event.
     */
    public function updated(Articlesubcategorie $articlesubcategorie): void
    {
        //
    }

    /**
     * Handle the Articlesubcategorie "deleted" event.
     */
    public function deleted(Articlesubcategorie $articlesubcategorie): void
    {
        $this->siteMapService->generation();
    }

    /**
     * Handle the Articlesubcategorie "restored" event.
     */
    public function restored(Articlesubcategorie $articlesubcategorie): void
    {
        //
    }

    /**
     * Handle the Articlesubcategorie "force deleted" event.
     */
    public function forceDeleted(Articlesubcategorie $articlesubcategorie): void
    {
        //
    }
}
