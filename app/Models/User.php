<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'type',
        'password',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Token name for passport authentication
     */
    public const PASSPORT_TOKEN_NAME = 'personal_token';

    /**
     * Const for type user
     */
    public const TYPE_USER = 'user';

    /**
     * Const for type admin
     */
    public const TYPE_ADMIN = 'admin';

    /**
     * Determine if this user is admin or not
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->type === 'admin';
    }
}
