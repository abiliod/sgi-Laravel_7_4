<?php

namespace App\Models\Correios;

use Illuminate\Database\Eloquent\Model;

class ClienteMonitoramento extends Model
{
    protected $fillable= [
        'cliente',
        'mcu_cliente',
        'codigo'
     ];

     public function Unidade()
     {
    //     return $this->belongsTo('App\Models\Correios\Unidade','mcu');
     }

}
