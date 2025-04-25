<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiseaseImage extends Model
{
    /** @use HasFactory<\Database\Factories\DiseaseImageFactory> */
    use HasFactory;

    protected $fillable = [
        'disease_id',
        'image',
    ];

    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }
}
