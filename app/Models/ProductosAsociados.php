<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductosAsociados extends Model
{
    protected $table = 'productos_asociados';

    protected $fillable = [];

    public function categoria(){
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id');
    }

    public function productos(){
        return $this->belongsTo(Producto::class, 'producto_id', 'id');
    }

    public function subcategoria(){
        return $this->belongsTo(Subcategoria::class, 'subcategoria_id', 'id');
    }
}
