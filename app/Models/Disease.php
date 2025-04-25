<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    /** @use HasFactory<\Database\Factories\DiseaseFactory> */
    use HasFactory;


    protected $fillable = [
        'name',
        'scientific_name',
        'also_know_as',
        'type_disease',
        'description',
        'origin_name',
        
    ];

  
    public function images()
    {
        return $this->hasMany(DiseaseImage::class);
    }

    public function symptoms()
    {
        return $this->hasMany(Symptom::class);
    }

    public function solutions()
    {
        return $this->hasMany(Solution::class);
    }

    public function homeRemedys()
    {
        return $this->hasMany(HomeRemedy::class);
    }

    public function preventions()
    {
        return $this->hasMany(Prevention::class);
    }
    

    public function  diseaseUser()
    {
        return $this->hasMany(DiseaseUser::class);
    }


}
