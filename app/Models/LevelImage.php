<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelImage extends Model
{

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

}
