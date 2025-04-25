<?php

namespace App\Models;

use App\Observers\ArticleCategorieObserve;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

// #[ObservedBy([ArticleCategorieObserve::class])]
class Articlecategorie extends Model
{
    use HasFactory;

    protected $fillable = [

        'name',
        'image_name',
    ];

    public function articlesubcategories()
    {

        return $this->hasMany(Articlesubcategorie::class);
    }

    public function articles()
    {
        return $this->hasManyThrough(Article::class, Articlesubcategorie::class);
    }
}
