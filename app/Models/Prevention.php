<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prevention extends Model
{
    /** @use HasFactory<\Database\Factories\PreventionFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'disease_id',
    ];

    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }
}
