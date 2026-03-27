<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $fillable = [
   'nombre',
    'descripcion',
    'precio_total',
    'anticipo',
    'fecha_inicio',
    'fecha_fin',
    'ubicacion',
    'punto_encuentro',
    'hora_salida',
    'transporte',
    'capacidad',
    'cupos_totales',
    'cupos_disponibles',
];
}

