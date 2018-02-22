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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/error', function(){
    return view('errors.error403');
});

// Route::get('/index', 'API\TestController@index');

Route::namespace('API')->group(function(){
    Route::get('/code', 'YbApiController@getCode');
    Route::get('/token', 'YbApiController@getToken');
    Route::get('/index', 'YbApiController@index');
    Route::get('/revoke', 'YbApiController@revoke');
});