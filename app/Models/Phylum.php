<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phylum extends Model
{
    /** @use HasFactory<\Database\Factories\PhylumFactory> */
    use HasFactory;

    protected $fillable = ['name'];


    public function plants()
    {
        return $this->hasMany(Plant::class);
    }
}
