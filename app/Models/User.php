<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasUuids, Notifiable, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nickname',
        'is_email_enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_email_enabled' => 'boolean',
        ];
    }

    /**
     * Get the player attributes for the user. Loader eager for attribute definitions.
     */
    public function playerAttributes()
    {
        return $this->hasMany(PlayerAttribute::class)->with('playerAttributeDefinition');
    }

    /**
     * Get the player level progress for the user.
     */
    public function playerLevelProgress()
    {
        return $this->hasMany(PlayerLevelProgress::class);
    }

    /**
     * Get the level progress count - it is the count of progress from the playerLevelProgress.
     */
    public function getLevelProgressCount(): int
    {
        return $this->playerLevelProgress()->sum('progress');
    }
}
