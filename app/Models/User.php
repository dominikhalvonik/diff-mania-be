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
     * Get the user attributes for the user. Loader eager for attribute definitions.
     */
    public function userAttributes()
    {
        return $this->hasMany(UserAttribute::class)->with('userAttributeDefinition');
    }

    /**
     * Get the user level progress for the user.
     */
    public function userLevelProgress()
    {
        return $this->hasMany(UserLevelProgress::class);
    }

    /**
     * User User Boosters
     */
    public function userBoosters()
    {
        return $this->hasMany(UserBooster::class);
    }

    /**
     * Get the level progress count - it is the count of progress from the userLevelProgress.
     */
    public function getLevelProgressCount(): int
    {
        return $this->userLevelProgress()->sum('progress');
    }
}
