<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantTag extends Model
{
    protected $fillable = ['name'];

    public function plants()
    {
        return $this->belongsToMany(Plant::class);
    }
}
