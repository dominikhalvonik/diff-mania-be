<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name', 'description', 'amount', 'attribute_id', 'reward_id'];

    public function attribute()
    {
        return $this->belongsTo(PlayerAttributeDefinition::class);
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }
}
