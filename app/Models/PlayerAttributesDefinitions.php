<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerAttributesDefinitions extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function playerAttributes()
    {
        return $this->hasMany(PlayerAttributes::class);
    }

    public function getAttribute($attribute)
    {
        return $this->attributes[$attribute];
    }

    public function setAttribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;
    }

    public function hasAttribute($attribute)
    {
        return array_key_exists($attribute, $this->attributes);
    }

    public function removeAttribute($attribute)
    {
        unset($this->attributes[$attribute]);
    }

    public function getAttributes()
    {
        return $this->attributes;
    }
}
