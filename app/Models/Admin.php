<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Model implements Authenticatable,JWTSubject
{
    use AuthenticatableTrait;
    use HasFactory;
    protected $table = 'admin'; 
      protected $keyType = 'string'; // Specify the type of the primary key
    public $incrementing = false; // Disable auto-incrementing for UUIDs
    protected $fillable = [
        'id',
        'name',
        'username',
        'role',
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
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    protected $casts = [
        'password' => 'hashed',
    ];
}
