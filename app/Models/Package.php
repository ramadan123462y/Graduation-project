<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Package extends Model
{
    /** @use HasFactory<\Database\Factories\PackageFactory> */
    use HasFactory, HasTranslations;


    public $translatable = ['name' , 'description'];

    protected $fillable = [
        'name',
        'description',
        'price',
        'coins'
    ];
}
