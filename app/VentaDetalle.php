<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    protected $table = 'detalle_ventas';
    protected $fillable = [
        'idventa', 
        'idarticulo',
        'cantidad',
        'precio',
        'descuento'
    ];
    public $timestamps = false;
}
