<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    /** @use HasFactory<\Database\Factories\SymptomFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }
}
