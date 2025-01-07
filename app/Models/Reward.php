<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $fillable = ['attribute_id', 'amount'];

    public function attribute()
    {
        return $this->belongsTo(PlayerAttributeDefinition::class);
    }
}
