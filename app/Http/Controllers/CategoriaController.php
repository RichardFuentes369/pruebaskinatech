<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Validator;

class CategoriaController extends Controller
{

    protected $categoria;

    public function __construct(Categoria $categoria)
    {
        $this->categoria = $categoria;
    }

    public function categoriaAll(){

        // if(!auth()->user()){
        //     return response()->json([
        //         'message' => 'Usuario no esta autenticado',
        //     ], 401);
        // }

        $obtenerCategoria = Categoria::where('status','activo')->get();

        if($obtenerCategoria){
            return response()->json([
                'message' => 'Lista de categorias',
                'response' => $obtenerCategoria
            ], 200);
        }else{
            return response()->json([
                'message' => 'Categoria no existe',
                'response' => null
            ], 404);
        }

    }

    public function categoria($id){

        if(!auth()->user()){
            return response()->json([
                'message' => 'Usuario no esta autenticado',
            ], 401);
        }

        if (!is_numeric($id)) {
            return response()->json(['message' => 'El parámetro debe ser un número', 'response' => null], 400);
        }

        $obtenerCategoria = Categoria::where('id', $id)->first();

        if($obtenerCategoria){
            return response()->json([
                'message' => 'Categoria no '.$id,
                'response' => $obtenerCategoria
            ], 200);
        }else{
            return response()->json([
                'message' => 'Categoria no existe',
                'response' => null
            ], 404);
        }


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

        $nuevaCategoria = new Categoria();
        $nuevaCategoria->name = $request['nombre'];
        $nuevaCategoria->status = $request['estado'];
        $guardarCategoria = $nuevaCategoria->save();

        if($guardarCategoria){
            return response()->json([
                'message' => 'Categoria guardada exitosamente',
                'response' => $nuevaCategoria
            ], 200);
        }

        if(!$guardarCategoria){
            return response()->json([
                'message' => 'Categoria no se pudo guardar',
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

        $obtenerCategoria = Categoria::where('id', $id)->first();

        if($obtenerCategoria){
            $obtenerCategoria->delete();
            return response()->json([
                'message' => 'Categoria eliminada exitosamente',
                'response' => null
            ], 200);
        }

        return response()->json([
            'message' => 'Categoria no existe',
            'response' => null
        ], 404);
    }

    public function listar(){

        if(!auth()->user()){
            return response()->json([
                'message' => 'Usuario no esta autenticado',
            ], 401);
        }

        $modelo = $this->categoria;

        if($_GET['page'] && $_GET['perPage']){

            if($_GET['page'] == 1){
                $pageReal = $_GET['page'] - 1;
            }else{
                $pageReal = ($_GET['page'] - 1) * $_GET['perPage'];
            }

            $listaCategoria = $modelo->offset($pageReal)->limit($_GET['perPage']);
            $next = $modelo->offset($_GET['page'] * $_GET['perPage'])->limit($_GET['perPage']);
        }

        if($_GET['filtro_field'] && $_GET['filtro_word']){
            if($_GET['filtro_field'] == 'id' || $_GET['filtro_field'] == 'status'){
                $listaCategoria = $modelo->offset($pageReal)->limit($_GET['perPage'])->where($_GET['filtro_field'], $_GET['filtro_word']);
                $next = $modelo->offset($_GET['page'] * $_GET['perPage'])->where($_GET['filtro_field'], $_GET['filtro_word'])->limit($_GET['perPage']);
            }else{
                $listaCategoria = $modelo->offset($pageReal)->limit($_GET['perPage'])->where($_GET['filtro_field'], 'like', '%'.$_GET['filtro_word'].'%');
                $next = $modelo->offset($_GET['page'] * $_GET['perPage'])->where($_GET['filtro_field'], 'like', '%'.$_GET['filtro_word'].'%')->limit($_GET['perPage']);
            }
        }

        if($_GET['order'] &&  $_GET['field']){
            $listaCategoria = $listaCategoria->orderBy($_GET['field'], $_GET['order']);
            $next = $next->orderBy($_GET['field'], $_GET['order']);
        }else{
            $listaCategoria = $listaCategoria->orderBy('id', 'desc');
            $next = $modelo->orderBy('id', 'desc');
        }


        return response()->json([
            'message' => 'Lista Categorias',
            'response' => [
                'data' => $listaCategoria->get(),
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

        $obtenerCategoria = Categoria::where('id', $id)->first();

        if($obtenerCategoria){
            $obtenerCategoria->name = $request->nombre;
            $obtenerCategoria->status = $request->estado;
            $obtenerCategoria->save();
            return response()->json([
                'message' => 'Categoria actualizada exitosamente',
                'response' => null
            ], 200);
        }

        return response()->json([
            'message' => 'La categoria no existe',
            'response' => null
        ], 404);
    }

}
