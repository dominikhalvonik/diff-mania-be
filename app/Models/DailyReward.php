<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReward extends Model
{

    protected $fillable = [
        'user_id',
        'day',
        'coins',
        'opened'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function claim()
    {
        $this->update(['opened' => true]);
    }
}
