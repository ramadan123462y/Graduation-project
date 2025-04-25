<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiseaseUser extends Model
{
    /** @use HasFactory<\Database\Factories\DiseaseUserFactory> */
    use HasFactory;

    protected $table = 'disease_user';

    protected $fillable = ['disease_id', 'user_id', 'repetitions','image'];


    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
