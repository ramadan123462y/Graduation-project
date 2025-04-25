<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagePlant extends Model
{
    /** @use HasFactory<\Database\Factories\ImagePlantFactory> */
    use HasFactory;

    protected $fillable = [
        'plant_id',
        'image',
    ];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }
}
