<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PlayerAttributesDefinitions;

class PlayerAttributes extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'player_attributes_definition_id',
        'value',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function playerAttributesDefinition()
    {
        return $this->belongsTo(PlayerAttributesDefinitions::class);
    }

}
