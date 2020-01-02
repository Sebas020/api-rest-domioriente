<?php

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

use App\Http\Middleware\ApiAuthMiddleware;

Route::get('/', 'UserController@index');
Route::post('/register', 'UserController@register');
Route::post('/login', 'UserController@login');
Route::put('/update', 'UserController@update')->middleware(ApiAuthMiddleware::class);
Route::get('/get-user', 'UserController@getUser')->middleware(ApiAuthMiddleware::class);
Route::get('/get-all', 'UserController@getAll')->middleware(ApiAuthMiddleware::class);
//Routes para negocio
Route::get('/all', 'NegocioController@all')->middleware(ApiAuthMiddleware::class);
Route::post('/create', 'NegocioController@create')->middleware(ApiAuthMiddleware::class);
Route::put('/negocio/{nit}', 'NegocioController@update')->middleware(ApiAuthMiddleware::class);
Route::delete('/negocio/{nit}', 'NegocioController@destroy')->middleware(ApiAuthMiddleware::class);
Route::get('/negocio/{nit}', 'NegocioController@getOne')->middleware(ApiAuthMiddleware::class);
//Routes para producto
Route::get('/productos', 'ProductoController@index')->middleware(ApiAuthMiddleware::class);
Route::post('/save', 'ProductoController@create')->middleware(ApiAuthMiddleware::class);
Route::put('/producto/{id}', 'ProductoController@update')->middleware(ApiAuthMiddleware::class);
Route::delete('/producto/{codigo}/{nit?}', 'ProductoController@destroy')->middleware(ApiAuthMiddleware::class);
//Routes para servicio
Route::get('/servicios', 'ServicioController@index')->middleware(ApiAuthMiddleware::class);
Route::post('/servicios', 'ServicioController@storage')->middleware(ApiAuthMiddleware::class);
Route::put('/servicios/{codigo}', 'ServicioController@update')->middleware(ApiAuthMiddleware::class);
Route::delete('/servicios/{codigo}', 'ServicioController@destroy')->middleware(ApiAuthMiddleware::class);
Route::get('servicio/{codigo}', 'ServicioController@getOne');