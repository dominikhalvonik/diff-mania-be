<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'player_attribute_definition_id',
        'value',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function playerAttributeDefinition()
    {
        return $this->belongsTo(PlayerAttributeDefinition::class);
    }
}
