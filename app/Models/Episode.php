<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;

    protected $fillable = [
        'episode_name',
        'unlock_stars',
        'unlock_coins',
    ];

    protected $casts = [
        'unlock_stars' => 'integer',
        'unlock_coins' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    // Episode has many levels
    public function levels()
    {
        return $this->hasMany(Level::class);
    }

    // Get levels count
    public function getLevelsCount()
    {
        return $this->levels()->count();
    }
}
