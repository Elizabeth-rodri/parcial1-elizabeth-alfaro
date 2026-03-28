<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marca extends Model
{
    use SoftDeletes; // Permite eliminar de forma lógica (soft delete)

    protected $table = 'marcas'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'nombre' // Campo que se puede asignar masivamente
    ];
}