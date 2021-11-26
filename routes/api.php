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

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');
Route::post('register', 'Auth\RegisterController@register');




Route::namespace('API')->group(function () {
    Route::group(['prefix' => 'employers'], function(){
        Route::get('', 'EmployerController@index')->name('employers');
        
        Route::get('{id}', 'EmployerController@show');
        Route::post('', 'EmployerController@store');
        Route::post('verifyotp', 'EmployerController@verifyotp');
        Route::put('{id}', 'EmployerController@update');
        Route::delete('{id}', 'EmployerController@delete');
    });


    Route::group(['prefix' => 'employees'], function(){
        Route::get('', 'EmployeeController@index')->name('employees');
        Route::get('{id}', 'EmployeeController@show');
        Route::post('', 'EmployeeController@store');
        Route::post('verifyotp', 'EmployeeController@verifyotp');
        Route::put('{id}', 'EmployeeController@update');
        Route::delete('{id}', 'EmployeeController@delete');
    });
});

Route::group(['middleware' => 'auth:api'], function() {
    
    Route::namespace('API')->group(function () {
        Route::group(['prefix' => 'accounts'], function(){
            Route::post('', 'AccountController@store');
            Route::put('{id}', 'AccountController@update');
        });
    });
});