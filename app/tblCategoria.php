<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tblCategoria extends Model
{

    protected $table = 'tbl_categoria';

    public function productos(){
        return $this->hasMany('App\Producto', null, 'codigo');
    }
}