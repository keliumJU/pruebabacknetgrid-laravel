<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');

Route::post('range_users/','UserController@index');
Route::get('user/{id}','UserController@getUser');


//all routes that need jwt auth
Route::group(['middleware' => ['jwt.verify']], function() {

    Route::post('user','UserController@getAuthenticatedUser');
    Route::put('update/{id}','UserController@update');
    Route::delete('delete/{id}','UserController@destroy');

});