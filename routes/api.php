<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    ProductoController,
    CategoriaController,
    SubcategoriaController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});


Route::group([
    'prefix' => 'categoria',
], function ($router) {
    Route::get('/getAll', [CategoriaController::class, 'categoriaAll']);
    Route::get('/obtener-categoria/{id}', [CategoriaController::class, 'categoria']);
    Route::post('/agregar-categoria', [CategoriaController::class, 'agregar']);
    Route::get('/listar-categoria', [CategoriaController::class, 'listar']);
    Route::put('/editar-categoria/{id}', [CategoriaController::class, 'editar']);
    Route::delete('/eliminar-categoria/{id}', [CategoriaController::class, 'eliminar']);
});

Route::group([
    'prefix' => 'subcategoria'

], function ($router) {
    Route::get('/obtener-subcategoria/{id}', [SubcategoriaController::class, 'subcategoria']);
    Route::post('/agregar-subcategoria', [SubcategoriaController::class, 'agregar']);
    Route::get('/listar-subcategoria', [SubcategoriaController::class, 'listar']);
    Route::put('/editar-subcategoria/{id}', [SubcategoriaController::class, 'editar']);
    Route::delete('/eliminar-subcategoria/{id}', [SubcategoriaController::class, 'eliminar']);
});

Route::group([
    'prefix' => 'producto'
], function ($router) {
    Route::get('/obtener-producto/{id}', [ProductoController::class, 'producto']);
    Route::post('/agregar-producto', [ProductoController::class, 'agregar']);
    Route::get('/listar-producto', [ProductoController::class, 'listar']);
    Route::put('/editar-producto/{id}', [ProductoController::class, 'editar']);
    Route::delete('/eliminar-producto/{id}', [ProductoController::class, 'eliminar']);
});
