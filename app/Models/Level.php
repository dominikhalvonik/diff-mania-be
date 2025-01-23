<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'reward_coins',
        'episode_id',
    ];

    protected $casts = [
        'episode_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    // Level belongs to episode
    public function episode()
    {
        return $this->belongsTo(Episode::class);
    }

    // Level has many level progress
    public function userLevelProgress()
    {
        return $this->hasMany(UserLevelProgress::class);
    }
}
