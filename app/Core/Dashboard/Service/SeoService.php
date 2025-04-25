<?php



namespace App\Core\Dashboard\Service;

use App\Core\Dashboard\Repository\Seo\SeoRepository;

class SeoService
{


    public $seoRepository;

    public function __construct(SeoRepository $seoRepository)
    {

        $this->seoRepository = $seoRepository;
    }


    public function create(array $data)
    {




        $this->seoRepository->create($data);
    }

    public function updateOrCreateById($articleId, array $data)
    {

        $this->seoRepository->updateOrCreateById($articleId, $data);
    }
}
