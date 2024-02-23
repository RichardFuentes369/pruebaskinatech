<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Validator;

class ProductoController extends Controller
{
    protected $producto;

    public function __construct(Producto $producto)
    {
        $this->producto = $producto;
    }


    public function producto($id){

        if(!auth()->user()){
            return response()->json([
                'message' => 'Usuario no esta autenticado',
            ], 401);
        }

        if (!is_numeric($id)) {
            return response()->json(['message' => 'El parámetro debe ser un número', 'response' => null], 400);
        }

        $obtenerproducto = Producto::where('id', $id)->first();

        if($obtenerproducto){
            return response()->json([
                'message' => 'Producto existente',
                'response' => $obtenerproducto
            ], 200);
        }

        return response()->json([
            'message' => 'producto no existe',
            'response' => null
        ], 404);

    }

    public function agregar(Request $request){

        if(!auth()->user()){
            return response()->json([
                'message' => 'Usuario no esta autenticado',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string',
            'estado' => 'required|string',
            'status' => 'in:activo,inactivo'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $nuevaproducto = new Producto();
        $nuevaproducto->name = $request['nombre'];
        $nuevaproducto->status = $request['estado'];
        $guardarproducto = $nuevaproducto->save();

        if($guardarproducto){
            return response()->json([
                'message' => 'producto guardada exitosamente',
                'response' => $nuevaproducto
            ], 200);
        }

        if(!$guardarproducto){
            return response()->json([
                'message' => 'producto no se pudo guardar',
                'response' => null
            ], 404);
        }


    }

    public function eliminar($id){

        if(!auth()->user()){
            return response()->json([
                'message' => 'Usuario no esta autenticado',
            ], 401);
        }

        if (!is_numeric($id)) {
            return response()->json(['message' => 'El parámetro debe ser un número', 'response' => null], 400);
        }

        $obtenerproducto = producto::where('id', $id)->first();

        if($obtenerproducto){
            $obtenerproducto->delete();
            return response()->json([
                'message' => 'producto eliminada exitosamente',
                'response' => null
            ], 200);
        }

        return response()->json([
            'message' => 'producto no existe',
            'response' => null
        ], 404);
    }

    public function listar(){

        if(!auth()->user()){
            return response()->json([
                'message' => 'Usuario no esta autenticado',
            ], 401);
        }

        $modelo = $this->producto;

        if($_GET['page'] && $_GET['perPage']){

            if($_GET['page'] == 1){
                $pageReal = $_GET['page'] - 1;
            }else{
                $pageReal = ($_GET['page'] - 1) * $_GET['perPage'];
            }

            $listaproducto = $modelo->offset($pageReal)->limit($_GET['perPage']);
            $next = $modelo->offset($_GET['page'] * $_GET['perPage'])->limit($_GET['perPage']);
        }

        if($_GET['order'] &&  $_GET['field']){
            $listaproducto = $listaproducto->orderBy($_GET['field'], $_GET['order']);
            $next = $next->orderBy($_GET['field'], $_GET['order']);
        }else{
            $listaproducto = $listaproducto->orderBy('id', 'desc');
            $next = $modelo->orderBy('id', 'desc');
        }


        return response()->json([
            'message' => 'Lista productos',
            'response' => [
                'data' => $listaproducto->get(),
                'next' => (count($next->get()) > 0) ? $_GET['page'] + 1 : null,
                'back' => ($_GET['page'] == 1) ? null : $_GET['page'] - 1
            ]
        ], 200);
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
            'nombre' => 'required|string',
            'estado' => 'required|string',
            'status' => 'in:activo,inactivo'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $obtenerproducto = producto::where('id', $id)->first();

        if($obtenerproducto){
            $obtenerproducto->name = $request->nombre;
            $obtenerproducto->status = $request->estado;
            $obtenerproducto->save();
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
