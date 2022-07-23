<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadoController;
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

Route::get('/', function () {
    return view('welcome');
});
/*
//basic example
Route::get('/empleado', function(){
    return view('empleado.index');
});

//by method
Route::get('/empleado/create', [EmpleadoController::class,'create']);
*/
//Route::get('/empleado/create', [EmpleadoController::class,'create']);


//by resource
//Route::resource('empleado',EmpleadoController::class);
Route::resource('empleado', 'EmpleadoController');
