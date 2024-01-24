<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Pasien extends Model implements Authenticatable,JWTSubject
{
    use HasFactory;
    use AuthenticatableTrait;
    use HasFactory;
    protected $table = 'pasien'; 
    protected $keyType = 'string'; // Specify the type of the primary key
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama',
        'username',
        'alamat',
        'no_hp',
        'no_ktp',
        'no_rm',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
