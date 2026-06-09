<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
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

    public function agenteInmobiliario()
    {
        return $this->hasOne(AgenteInmobiliario::class);
    }

    public function inventarios()
    {
        return $this->hasMany(Inventario::class);
    }

    public function adminlte_image()
    {
        return '/assets/images/ICONO-3D-PAGINA-CARTAGENA-NORTE.png';
    }

    public function adminlte_desc()
    {
        /* return implode(User::getRoleNames()->toArray()); */
        return implode(', ', $this->getRoleNames()->toArray());
    }

    public function contractor()
    {
        return $this->hasOne(Contractor::class, 'user_id');
    }
}