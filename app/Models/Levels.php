<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\Episodes;

class Levels extends Model
{
    use HasFactory;

    protected $fillable = [
        'level_name',
        'unlock_stars',
        'unlock_coins',
        'episode_id',
    ];

    protected $casts = [
        'unlock_stars' => 'integer',
        'unlock_coins' => 'integer',
        'episode_id' => 'integer',
    ];

    // Level belongs to episode
    public function episode()
    {
        return $this->belongsTo(Episodes::class);
    }
}
