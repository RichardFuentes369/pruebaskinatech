<?php

namespace App\Http\Controllers;

use App\Models\ProductosAsociados;
use Illuminate\Http\Request;
use Validator;

class SubcategoriaProductoController extends Controller
{
    protected $productoAsociado;

    public function __construct(ProductosAsociados $productoAsociado)
    {
        $this->productoAsociado = $productoAsociado;
    }

    public function editar($id, Request $request){

        if(!auth()->user()){
            return response()->json([
                'message' => 'Usuario no esta autenticado',
            ], 401);
        }

        if (!is_numeric($id)) {
            return response()->json(['message' => 'El parámetro debe ser un número', 'response' => null], 400);
        }


        $validator = Validator::make($request->all(), [
            'subcategoria' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $obtenerAsociados = ProductosAsociados::where('subcategoria_id', $id)->get();

        if($obtenerAsociados){
            foreach ($obtenerAsociados as $value) {
                $value->categoria_id = intval($request->subcategoria);
                $value->save();
            }
            return response()->json([
                'message' => 'producto actualizada exitosamente',
                'response' => null
            ], 200);
        }

        return response()->json([
            'message' => 'La producto no existe',
            'response' => null
        ], 404);
    }
}
