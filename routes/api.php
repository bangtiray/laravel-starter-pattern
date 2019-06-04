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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['namespace' => 'API', 'prefix' => 'v1', 'as' => 'v1.'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'UserController@login');
        Route::post('register', 'UserController@register');
        Route::get('activation/{confirmid}', 'UserController@activation');
    });
    
    Route::group(['middleware' => 'auth:api'], function(){
        Route::group(['prefix' => 'user'], function () {
            Route::post('details', 'UserController@details');
        });
    });
});