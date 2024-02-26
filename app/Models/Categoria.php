<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = [
        'nombre_categoria', 'estado'
    ];

    public function productos(){
        return $this->hasMany(ProductosAsociados::class, 'categoria_id', 'id');
    }

    public function subcategorias(){
        return $this->hasMany(Subcategoria::class, 'categoria_id', 'id');
    }
}
