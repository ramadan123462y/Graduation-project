<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Plant extends Model
{
    /** @use HasFactory<\Database\Factories\PlantsFactory> */
    use HasFactory , HasTranslations;


   // public $translatable = ['name' , 'description' ,'watering_anmount' , 'soil','nutrients'];

    protected $guarded = [];

    protected function casts(): array
{
    return [
        'pests' => 'array',
        'diseases' => 'array',
        'also_known_as' => 'array',
      
    ];
}


    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function genus()
    {
        return $this->belongsTo(Genus::class);
    }

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function phylum()
    {
        return $this->belongsTo(Phylum::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function drainage()
    {
        return $this->belongsTo(Drainage::class);
    }

    public function images()
    {
        return $this->hasMany(ImagePlant::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }
    
}
