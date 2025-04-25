<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use stdClass;

use function PHPSTORM_META\map;

class ArticleDetailsResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $data = [];



        $data['userName'] = $this->user->name ?? $this->admin->name;
        $data['userImageUrl'] = URL::asset('Backend/Uploades/Users/' . $this->user->image_name);
        $data['createdAt'] = Carbon::parse($this->created_at)->toDateString();
        $data['title'] = $this->title;
        $data['subTitle'] = mb_substr($this->title, 0, 13, 'UTF-8');;

        $data['viewsCount'] = $this->count_views;


        $data['readTime'] = $this->handleReadTime($this->descreptionArticles);

        $data['descriptions'] = $this->descreptionArticles()->orderBy('order')->get()->map(function ($descriptionArticle) {


            return [
                "title" => $descriptionArticle->title,
                "content" => $descriptionArticle->content,
                "article_id" => $descriptionArticle->article_id,
                'order' => $descriptionArticle->order
            ];
        })->toArray();
        $data['seo'] = $this->seo  ?? new stdClass();

        return $data;
    }





    public function handleReadTime($descreptionArticles)
    {
        $readTime = 0;
        $content = '';

        foreach ($descreptionArticles as $descriptionArticle) {

            $cleanContent = strip_tags($descriptionArticle->content);


            $cleanContent = preg_replace('/&nbsp;|[\r\n\t]+/', ' ', $cleanContent);


            $cleanContent = preg_replace('/\s+/', ' ', $cleanContent);


            $content .= $cleanContent . ' ';
        }


        $charCount = strlen($content);


        $readingSpeed = 1000;


        $readTime = ceil($charCount / $readingSpeed);

        return $readTime;
    }
}
