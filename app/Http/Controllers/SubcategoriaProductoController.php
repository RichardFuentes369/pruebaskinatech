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

    public function listar(){

        if(!auth()->user()){
            return response()->json([
                'message' => 'Usuario no esta autenticado',
            ], 401);
        }

        $modelo = $this->productoAsociado;

        if($_GET['page'] && $_GET['perPage']){

            if($_GET['page'] == 1){
                $pageReal = $_GET['page'] - 1;
            }else{
                $pageReal = ($_GET['page'] - 1) * $_GET['perPage'];
            }

            $listaproductosasociados = $modelo->with('categoria')->with('subcategoria')->with('productos')->offset($pageReal)->limit($_GET['perPage']);
            $next = $modelo->offset($_GET['page'] * $_GET['perPage'])->limit($_GET['perPage']);
        }

        if($_GET['filtro_field'] && $_GET['filtro_word']){
            if($_GET['filtro_field'] == 'id'){
                $listaproductosasociados = $modelo->with('categoria')->with('subcategoria')->with('productos')->offset($pageReal)->limit($_GET['perPage'])->where($_GET['filtro_field'], $_GET['filtro_word']);
                $next = $modelo->offset($_GET['page'] * $_GET['perPage'])->where($_GET['filtro_field'], $_GET['filtro_word'])->limit($_GET['perPage']);
            }else{
                $listaproductosasociados = $modelo->with('categoria')->with('subcategoria')->with('productos')->offset($pageReal)->limit($_GET['perPage'])->where($_GET['filtro_field'], 'like', '%'.$_GET['filtro_word'].'%');
                $next = $modelo->offset($_GET['page'] * $_GET['perPage'])->where($_GET['filtro_field'], 'like', '%'.$_GET['filtro_word'].'%')->limit($_GET['perPage']);
            }
        }

        if($_GET['order'] &&  $_GET['field']){
            $listaproductosasociados = $listaproductosasociados->orderBy($_GET['field'], $_GET['order']);
            $next = $next->orderBy($_GET['field'], $_GET['order']);
        }else{
            $listaproductosasociados = $listaproductosasociados->orderBy('id', 'desc');
            $next = $modelo->orderBy('id', 'desc');
        }

        return response()->json([
            'message' => 'Lista productos',
            'response' => [
                'data' => $listaproductosasociados->get(),
                'next' => (count($next->get()) > 0) ? $_GET['page'] + 1 : null,
                'back' => ($_GET['page'] == 1) ? null : $_GET['page'] - 1
            ]
        ], 200);
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

        $obtenerproducto = ProductosAsociados::where('id', $id)->first();

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
}
