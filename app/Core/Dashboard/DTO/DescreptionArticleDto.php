<?php

namespace App\Core\Dashboard\DTO;

class DescreptionArticleDto
{


    public $title, $content, $articleId;

    public function __construct($title, $content, $articleId)
    {

        $this->title = $title;
        $this->content = $content;
        $this->articleId = $articleId;
    }

    public function getTitle()
    {

        return $this->title;
    }

    public function getContent()
    {

        return $this->content;
    }

    public function getArticleId()
    {

        return $this->articleId;
    }

   
}
