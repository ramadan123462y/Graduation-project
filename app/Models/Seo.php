<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'meta_description',
        'url',
        'keywords',
        'article_id',
    ];

    public function article()
    {


        return $this->belongsTo(Article::class);
    }
}
