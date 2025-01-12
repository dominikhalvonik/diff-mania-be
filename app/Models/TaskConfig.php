<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskConfig extends Model
{
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function userAttributeDefinition()
    {
        return $this->belongsTo(UserAttributeDefinition::class);
    }

    public function booster()
    {
        return $this->belongsTo(Booster::class);
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }
}
