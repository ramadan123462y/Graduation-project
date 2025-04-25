<?php


namespace App\Core\Dashboard\Strategy\ArticleAttachments;



interface ArticleAttachmentsInterface
{
    public function save($request, $article);
}
