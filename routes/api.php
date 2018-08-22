<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', 'api\AuthController@register2');
Route::post('/login', 'api\AuthController@login');
Route::post('/verify/{id}', 'api\AuthController@verifyUser');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/index', 'api\userController@index');
    Route::get('/show/{id}', 'api\userController@show');
    Route::post('/update/{id}', 'api\userController@update');
    Route::post('/delete/{id}', 'api\userController@delete');
});
