<?php

namespace App\Observers;

use App\Core\Dashboard\Service\SiteMapService;
use App\Models\Articlecategorie;
use Illuminate\Support\Facades\Log;

class ArticleCategorieObserve
{

    public $siteMapService;

    public function __construct(SiteMapService $siteMapService)
    {
        $this->siteMapService = $siteMapService;
    }


    /**
     * Handle the Articlecategorie "created" event.
     */
    public function created(Articlecategorie $articlecategorie): void
    {
        $status = $this->siteMapService->generation();

    }

    /**
     * Handle the Articlecategorie "updated" event.
     */
    public function updated(Articlecategorie $articlecategorie): void
    {
        //
    }

    /**
     * Handle the Articlecategorie "deleted" event.
     */
    public function deleted(Articlecategorie $articlecategorie): void
    {
        $status = $this->siteMapService->generation();

    }

    /**
     * Handle the Articlecategorie "restored" event.
     */
    public function restored(Articlecategorie $articlecategorie): void
    {
        //
    }

    /**
     * Handle the Articlecategorie "force deleted" event.
     */
    public function forceDeleted(Articlecategorie $articlecategorie): void
    {
        //
    }
}
