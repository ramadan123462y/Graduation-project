<?php

namespace App\Http\Controllers\Api;

use App\Core\Dashboard\MessageConstants;
use App\Core\Dashboard\Service\SiteMapService;
use App\Http\Controllers\Controller;



use Illuminate\Support\Facades\URL as URLFacade;

class SiteMapController extends Controller
{

    public $siteMapService;


    public function __construct(SiteMapService $siteMapService)
    {


        $this->siteMapService = $siteMapService;
    }
    public function generateSitemap()
    {

        $status =  $this->siteMapService->generation();

        if ($status == true) {



            return apiResponse(
                [

                    'url' => URLFacade::asset('sitemap.xml')
                ],
                MessageConstants::SITEMAP_GENERATED,
                200
            );
        } else {

      


            return   apiResponse([], 'File not found', 404);
        }
    }
}
