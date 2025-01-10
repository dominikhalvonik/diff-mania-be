<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_attribute_definition_id',
        'value',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userAttributeDefinition()
    {
        return $this->belongsTo(UserAttributeDefinition::class);
    }
}
