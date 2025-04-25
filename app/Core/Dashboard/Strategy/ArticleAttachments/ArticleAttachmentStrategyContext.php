<?php




namespace App\Core\Dashboard\Strategy\ArticleAttachments;

use App\Core\Dashboard\Service\ArticleService;


class ArticleAttachmentStrategyContext
{



    public ArticleAttachmentsInterface $articleAttachmentsStrategy;


    public function __construct(string $option, ArticleService $articleService)
    {


        switch ($option) {
            case 'banner':
                $this->articleAttachmentsStrategy = new ArticleBannerStrategy($articleService);

                break;
            case 'gallery':

                $this->articleAttachmentsStrategy = new ArticleGalleryStrategy($articleService);

                break;
            case 'video':

                $this->articleAttachmentsStrategy = new ArticleVideoStrategy($articleService);

                break;
            case 'before_after':
                $this->articleAttachmentsStrategy = new ArticleBeforeAfterStrategy($articleService);

                break;
        }
    }


    public function save($request, $article)
    {

        $this->articleAttachmentsStrategy->save($request, $article);
    }
}
