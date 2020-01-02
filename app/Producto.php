<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'tbl_producto';
    
    public function negocios(){
        return $this->belongsToMany('App\Negocio', 'tbl_negocio_producto', 'codigo_producto', 'codigo_negocio', 'codigo', 'nit');
    }
    
    public function categorias(){
        return $this->belongsTo('App\tblCategoria', 'categoria', 'codigo');
    }
}
