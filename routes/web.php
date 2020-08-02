<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware'=>['guest']],function(){
    Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login')->name('login.post');
});

Route::group(['middleware'=>['auth']],function(){
    
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::view('/main','contenido.contenido')->name('main');
    
    Route::group(['middleware'=>['Almacenero']],function(){
        
        Route::get('/categoria', 'CategoriaController@index');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        Route::get('/categoria/select_categoria', 'CategoriaController@selecCategoria');

        Route::get('/articulo', 'ArticuloController@index');
        Route::post('/articulo/registrar', 'ArticuloController@store');
        Route::put('/articulo/actualizar', 'ArticuloController@update');
        Route::put('/articulo/desactivar', 'ArticuloController@desactivar');
        Route::put('/articulo/activar', 'ArticuloController@activar');
        
        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');
        
    });
    
    Route::group(['middleware'=>['Vendedor']],function(){

        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');
    });

    Route::group(['middleware'=>['Administrador']],function(){

        Route::get('/rol', 'RoleController@index');
        Route::get('/rol/selectRol', 'RoleController@selecRol');
        
        
        Route::get('/user', 'UsuarioController@index');
        Route::post('/user/registrar', 'UsuarioController@store');
        Route::put('/user/actualizar', 'UsuarioController@update');
        Route::put('/user/desactivar', 'UsuarioController@desactivar');
        Route::put('/user/activar', 'UsuarioController@activar');

    });
        
});

//Route::get('/home', 'HomeController@index')->name('home');