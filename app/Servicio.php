<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model {

    protected $table = 'tbl_servicio';
    //Relación de muchos a muchos
    public function productos() {
        return $this->belongsToMany('App\Producto','tbl_servicio_producto', 'codigo_producto', 'codigo_servicio', 'codigo', 'codigo');
    }

    public function user(){
        return $this->hasMany('App\tblUsuario', 'docid', 'cliente');
    }

    public function domiciliario(){
        return $this->hasMany('App\tblUsuario', 'docid', 'domiciliario');
    }
}