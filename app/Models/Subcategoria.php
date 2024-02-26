<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    use HasFactory;

    public function categoria(){
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id');
    }

    public function productos(){
        return $this->hasMany(ProductosAsociados::class, 'subcategoria_id', 'id');
    }
}
