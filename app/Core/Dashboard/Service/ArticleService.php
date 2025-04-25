<?php

namespace App\Core\Dashboard\Service;

use App\Core\Dashboard\DTO\ArticleDto;
use App\Core\Dashboard\DTO\BannerDto;
use App\Core\Dashboard\DTO\BeforeAfterDto;
use App\Core\Dashboard\DTO\DescreptionArticleDto;
use App\Core\Dashboard\DTO\GalleryDto;
use App\Core\Dashboard\DTO\VideoDto;
use App\Core\Dashboard\Repository\Article\ArticleInterface;

use App\Core\Dashboard\Repository\DescreptionArticle\DescreptionArticleInterface;
use App\Core\Dashboard\Strategy\ArticleAttachments\ArticleAttachmentStrategyContext;
use App\Core\Trait\FileTrait;
use App\Http\Requests\ArticleRequest;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArticleService
{

    use FileTrait;

    public $articleInterface;
    public $descreptionArticleInterface;
    public $bannerService;
    public $videoService;
    public $beforeAfterService;
    public $galleryService;
    public $seoService;

    public function __construct(
        ArticleInterface $articleInterface,
        DescreptionArticleInterface $descreptionArticleInterface,
        BannerService $bannerService,
        VideoService $videoService,
        BeforeAfterService $beforeAfterService,
        SeoService $seoService,
        GalleryService $galleryService
    ) {


        $this->articleInterface = $articleInterface;
        $this->descreptionArticleInterface = $descreptionArticleInterface;
        $this->bannerService = $bannerService;
        $this->videoService = $videoService;
        $this->galleryService = $galleryService;
        $this->beforeAfterService = $beforeAfterService;
        $this->seoService = $seoService;
    }



    public function createArticleDto($request)
    {
        $imageName = null;
        if ($request->file('image_file')) {

            $imageName = Str::uuid() . '.' . $request->file('image_file')->getClientOriginalExtension();
        }

        return new ArticleDto(
            $request->main_title,
            $request->is_new,
            $request->file('image_file'),
            $request->articlesubcategorie_id,
            $request->user_id,
            $imageName,
            $request->most_famous,
            $request->home_page,
            $request->status
        );
    }

    public function store(ArticleRequest $request)
    {

        validator($request->all(), [

            'image_file' => 'required|image'
        ])->validate();

        $articleDto = $this->createArticleDto($request);


        $article = $this->articleInterface->create($articleDto);

        FileTrait::uploade($articleDto->getImageFile(), $articleDto->getImageName(), 'Articles', 'uploades');



        $this->createDescriptions(
            $request->title,
            $request->froala_content,
            $article->id
        );


    }

    private function prepareSeoData($titleSeo, $metaDescriptionSeo, $urlSeo, $keywordsSeo, $articleId)
    {

        $seoData = [

            'title' => $titleSeo,
            'meta_description' => $metaDescriptionSeo,
            'url' => $urlSeo,
            'keywords' => $keywordsSeo,
            'article_id' => $articleId
        ];

        return $seoData;
    }


    public function update(ArticleRequest $request)
    {


        $article = $this->articleInterface->findOrFailWithRelation(
            $request->article_id,
            'articlesubcategorie',
            'user',
            'descreptionArticles',
            'subUsers',
            'banner',
            'galleries',
            'video',
            'beforeafter'
        );
        $articleDto = $this->createArticleDto($request);

        $data = [
            'title' => $articleDto->getTitle(),
            'is_new' => $articleDto->getIsNew(),
            'articlesubcategorie_id' => $articleDto->getArticleSubCategorieId(),
            'user_id' => $articleDto->getUserId(),
            'home_page' => $articleDto->getHomePage(),
            'most_famous' => $articleDto->getMostFamous(),

        ];

        if (isset($request->video_home_page)) {
            $article->video()->update([

                'home_page' => $request->video_home_page,
            ]);
        }

        if ($request->file('image_file')) {


            $data['image_file'] = $this->handleImageFile($request, $article, $articleDto);
        }


        $this->articleInterface->update($article, $data);


        $article->descreptionArticles()->delete();

        $this->createDescriptions(
            $request->title,
            $request->froala_content,
            $article->id
        );


    }

    public function deleteBanner($article)
    {

        FileTrait::delete(public_path(config('constants.article_upload_path') . '/Banners/' . $article->banner->image_name));

        $this->articleInterface->deleteBanner($article);
    }

    public function deleteVideo($article)
    {

        FileTrait::delete(public_path(config('constants.article_upload_path') . '/Videos/' . $article->video->video_name));

        $this->articleInterface->deleteVideo($article);
    }

    public function deleteBeforeAfter($article)
    {

        FileTrait::delete(public_path(config('constants.article_upload_path') . '/BeforeAndAfter/' . $article->beforeafter->image_before));
        FileTrait::delete(public_path(config('constants.article_upload_path') . '/BeforeAndAfter/' . $article->beforeafter->image_after));

        $this->articleInterface->deleteBeforeAndAfter($article);
    }

    public function deleteGalleries($article, $galleries)
    {

        foreach ($galleries as $gallerie) {


            FileTrait::delete(public_path(config('constants.article_upload_path') . '/Galleryes/' . $gallerie->image_file));
        }

        $this->articleInterface->deleteGalleries($article);
    }

    private function deleteArticleOptions($article)
    {
        if (isset($article->banner)) {

            $this->deleteBanner($article);
        } elseif (isset($article->video)) {

            $this->deleteVideo($article);
        } elseif (isset($article->beforeafter)) {

            $this->deleteBeforeAfter($article);
        } elseif (isset($article->galleries) && count($article->galleries) > 0) {

            $this->deleteGalleries($article, $article->galleries);
        }
    }

    public function delete($id)
    {


        $article = $this->articleInterface->findOrFailWithRelation($id);

        if (!$article) {

            abort(404, 'Article not found.');
        }

        $this->deleteArticleOptions($article);

        $article->delete();
    }



    private function handleImageFile($request, $article, $articleDto)
    {


        $validator = validator($request->all(), [

            'image_file' => 'required|image'
        ])->validate();

        FileTrait::delete(public_path(config('constants.article_upload_path') . '/' . $article->image_file));

        $data['image_file'] = $articleDto->getImageName();


        FileTrait::uploade($articleDto->getImageFile(), $articleDto->getImageName(), 'Articles', 'uploades');

        return $data['image_file'];
    }

    public function handleBannerOption($request, $article)
    {
        $file = $request->file('banner');
        $imageName = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $bannerDto = new BannerDto($imageName, $article->id, $file);

        $this->bannerService->create($bannerDto);
    }

    public function handleVideoOption($request, $article)
    {

        $videoDto = new VideoDto(Str::uuid() . '.' . $request->file('video')->getClientOriginalExtension(), $article->id, $request->file('video'), $request->video_home_page);
        $this->videoService->create($videoDto);
    }

    public function handleGalleryOption($request, $article)
    {


        foreach ($request->file('gallery') as $gallery) {


            $galleryDto = new GalleryDto($gallery, $article->id, Str::uuid() . '.' . $gallery->getClientOriginalExtension());

            $this->galleryService->create($galleryDto);
        }
    }


    private function processArticleOption($option, $request, $article)
    {

        (new ArticleAttachmentStrategyContext($option, $this))->save($request, $article);



    }

    public function handleBeforeAfterOption($request, $article)
    {

        $beforeAfterDto = new BeforeAfterDto(
            $request->file('before'),
            $request->file('after'),


            $article->id,
            Str::uuid() . '.' . $request->file('before')->getClientOriginalExtension(),
            Str::uuid() . '.' . $request->file('after')->getClientOriginalExtension()


        );

        $this->beforeAfterService->create($beforeAfterDto);
    }

    public function createDescriptions($titles, $contents, $articleId )
    {

        foreach ($titles as $key => $title) {
            $descreptionArticleDto = new DescreptionArticleDto($title, $contents[$key], $articleId);

            $this->descreptionArticleInterface->create($descreptionArticleDto);
        }
    }



    public function uploadeImageDescription($file)
    {

        $imageName = Str::uuid() . '.' . $file->getClientOriginalExtension();

        FileTrait::uploade($file, $imageName, 'Articles', 'uploades');
        return $path = asset(config('constants.article_upload_path') . '/' . $imageName);
    }
}
