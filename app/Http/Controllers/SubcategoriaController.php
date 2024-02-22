<?php

namespace App\Http\Controllers;

use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Validator;


class SubcategoriaController extends Controller
{
    protected $subcategoria;

    public function __construct(Subcategoria $subcategoria)
    {
        $this->subcategoria = $subcategoria;
    }


    public function subcategoria($id){

        if(!auth()->user()){
            return response()->json([
                'message' => 'Usuario no esta autenticado',
            ], 401);
        }

        $obtenersubcategoria = $this->subcategoria::where('id', $id)->first();

        if($obtenersubcategoria){
            return response()->json([
                'message' => 'subcategoria existente',
                'response' => $obtenersubcategoria
            ], 200);
        }

        return response()->json([
            'message' => 'subcategoria no existe',
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

        $nuevasubcategoria = new $this->subcategoria;
        $nuevasubcategoria->name = $request['nombre'];
        $nuevasubcategoria->status = $request['estado'];
        $guardarsubcategoria = $nuevasubcategoria->save();

        if($guardarsubcategoria){
            return response()->json([
                'message' => 'subcategoria guardada exitosamente',
                'response' => $nuevasubcategoria
            ], 200);
        }

        if(!$guardarsubcategoria){
            return response()->json([
                'message' => 'subcategoria no se pudo guardar',
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

        $obtenersubcategoria = $this->subcategoria::where('id', $id)->first();

        if($obtenersubcategoria){
            $obtenersubcategoria->delete();
            return response()->json([
                'message' => 'subcategoria eliminada exitosamente',
                'response' => null
            ], 200);
        }

        return response()->json([
            'message' => 'subcategoria no existe',
            'response' => null
        ], 404);
    }

    public function listar(){

        if(!auth()->user()){
            return response()->json([
                'message' => 'Usuario no esta autenticado',
            ], 401);
        }

        $modelo = $this->subcategoria;

        if($_GET['page'] && $_GET['perPage']){

            if($_GET['page'] == 1){
                $pageReal = $_GET['page'] - 1;
            }else{
                $pageReal = ($_GET['page'] - 1) * $_GET['perPage'];
            }

            $listasubcategoria = $modelo->offset($pageReal)->limit($_GET['perPage']);
            $next = $modelo->offset($_GET['page'] * $_GET['perPage'])->limit($_GET['perPage']);
        }

        if($_GET['order'] &&  $_GET['field']){
            $listasubcategoria = $listasubcategoria->orderBy($_GET['field'], $_GET['order']);
            $next = $next->orderBy($_GET['field'], $_GET['order']);
        }else{
            $listasubcategoria = $listasubcategoria->orderBy('id', 'desc');
            $next = $modelo->orderBy('id', 'desc');
        }


        return response()->json([
            'message' => 'Lista subcategorias',
            'response' => [
                'data' => $listasubcategoria->get(),
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

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string',
            'estado' => 'required|string',
            'status' => 'in:activo,inactivo'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $obtenersubcategoria = $this->subcategoria::where('id', $id)->first();

        if($obtenersubcategoria){
            $obtenersubcategoria->name = $request->nombre;
            $obtenersubcategoria->status = $request->estado;
            $obtenersubcategoria->save();
            return response()->json([
                'message' => 'subcategoria actualizada exitosamente',
                'response' => null
            ], 200);
        }

        return response()->json([
            'message' => 'La subcategoria no existe',
            'response' => null
        ], 404);
    }

}
