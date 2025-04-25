<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class ArticleDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'statics' => [

                'title' => $this->title,
                'date' => optional($this->created_at)->format('Y-m-d'),
                'imageUrl' => URL::asset('Backend/Uploades/Articles/' . $this->image_file),
                'viewsCount' => $this->count_views,

            ],
            'descreptions' => $this->descreptionArticles->map(function ($item) {

                return [

                    'title' => $item->title,
                    'content' => $item->content
                ];
            })
        ];
    }
}
