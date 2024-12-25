<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerLevelProgress extends Model
{
    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
