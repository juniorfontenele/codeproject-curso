<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'client'], function() {
    Route::get('/', 'ClientController@index');
    Route::post('/', 'ClientController@store');
    Route::get('/{id}', 'ClientController@show')->where(['id' => '\d+']);
    Route::put('/{id}', 'ClientController@update')->where(['id' => '\d+']);
    Route::delete('/{id}', 'ClientController@destroy')->where(['id' => '\d+']);
});

Route::group(['prefix' => 'project'], function() {
    Route::get('/', 'ProjectController@index');
    Route::post('/', 'ProjectController@store');
    Route::get('/{id}', 'ProjectController@show')->where(['id' => '\d+']);
    Route::put('/{id}', 'ProjectController@update')->where(['id' => '\d+']);
    Route::delete('/{id}', 'ProjectController@destroy')->where(['id' => '\d+']);
});