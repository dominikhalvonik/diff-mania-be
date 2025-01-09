<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBooster extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'booster_id',
    ];

    public function booster()
    {
        return $this->belongsTo(Booster::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
