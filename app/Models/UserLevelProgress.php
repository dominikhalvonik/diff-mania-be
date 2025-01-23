<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLevelProgress extends Model
{

    protected $fillable = [
        'user_id',
        'level_id',
        'progress',
    ];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
