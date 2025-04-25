<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class ArticleResource extends JsonResource
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
            'title' => $this->title,
            'imageUrl' => URL::asset('Backend/Uploades/Articles/' . $this->image_file),
            'date' => optional($this->created_at)->format('Y-m-d'),
            'viewsCount' => $this->count_views,
            'description' => optional($this->descreptionArticles()->first())->content ?? ""
        ];
    }
}
