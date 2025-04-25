<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class SubCategorieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [


            'id' => $this->id,
            'name' => $this->name,
            'imageUrl' => URL::asset("Backend/Uploades/Articles/SubCategories/" . $this->image_name),
            'descreption' => $this->descreption,
            'articleCount' => $this->articles_count,
            'viewArticlesCount' => $this->articles_sum_count_views,

        ];
    }
}
