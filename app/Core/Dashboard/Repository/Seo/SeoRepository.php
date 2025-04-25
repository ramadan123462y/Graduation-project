<?php

namespace App\Core\Dashboard\Repository\Seo;

use App\Models\Seo;

class SeoRepository
{


    public function create(array $data)
    {

        Seo::create($data);
    }

    public function updateOrCreateById($articleId, array $data)
    {


        Seo::updateOrCreate(
            [

                'article_id' => $articleId
            ],
            $data

        );
    }
}
