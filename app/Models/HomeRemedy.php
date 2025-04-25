<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeRemedy extends Model
{
    /** @use HasFactory<\Database\Factories\HomeRemedyFactory> */
    use HasFactory;

    protected $fillable = [
        'disease_id',
        'title',
        'description',
        'image',
    ];

    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }
}
