<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class tblUsuario extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'docid','nombres', 'apellidos', 'email', 'clave', 'celular', 'tel_fijo', 'fecha_nacimiento', 'foto', 'direccion', 'conacto', 'negocio', 'tipo_usuario', 'created_at', 'updated_at', 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'clave', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function negocio(){
        return $this->belongsTo('App\Negocio', 'negocio', 'nit');
    }

    public function tipo_usuario(){
        return $this->belongsTo('App\TipoUsuario', 'tipo_usuario', 'codigo');
    }

}
