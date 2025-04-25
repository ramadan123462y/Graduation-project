<?php

namespace App\Models;

use App\Observers\ArticleObserve;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

// #[ObservedBy([ArticleObserve::class])]

class Article extends Model
{
    use HasFactory;

    protected $fillable = [

        'title',
        'is_new',
        'home_page',
        'most_famous',
        'image_file',
        'count_views',
        'articlesubcategorie_id',
        'status',
        'user_id',
        'admin_id'
    ];
    public function articlesubcategorie()
    {


        return $this->belongsTo(Articlesubcategorie::class);
    }

    public function user()
    {

        return $this->belongsTo(User::class,'user_id');
    }

    public function descreptionArticles()
    {


        return $this->hasMany(DescreptionArticle::class);
    }

    public function subUsers()
    {


        return $this->belongsToMany(User::class, 'article_user', 'article_id', 'user_id');
    }

    public function banner()
    {


        return $this->hasOne(Banner::class);
    }

    public function galleries()
    {


        return $this->hasMany(Gallery::class);
    }

    public function video()
    {


        return $this->hasOne(Video::class);
    }

    public function beforeafter()
    {

        return $this->hasOne(BeforeAfter::class);
    }

    public function seo()
    {

        return $this->hasOne(Seo::class);
    }

    public function admin()
    {

        return $this->belongsTo(Admin::class);
    }

    public function scopeActive(Builder $builder): void
    {
        $builder->where('status', true);
    }
    public function scopeInActive(Builder $builder): void
    {
        $builder->where('status', false);
    }
}
