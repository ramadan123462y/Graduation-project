<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genus extends Model
{
    /** @use HasFactory<\Database\Factories\GenusFactory> */
    use HasFactory;

    public $table = 'genera';

    protected $fillable = ['name'];


    public function plants()
    {
        return $this->hasMany(Plant::class);
    }
}
