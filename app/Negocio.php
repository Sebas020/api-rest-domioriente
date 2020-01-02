<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Negocio extends Model
{
    protected $table = 'tbl_negocio';
    
    public function productos(){
        return $this->belongsToMany('App\Producto', 'tbl_negocio_producto', 'codigo_negocio', 'codigo_producto', 'nit', 'codigo');
    }

    public function user(){
    	return $this->belongsTo('App\tblCategoria', 'categoria', 'codigo');
    }
}
