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
    Route::get('/{id}', 'ClientController@show');
    Route::put('/{id}', 'ClientController@update');
    Route::delete('/{id}', 'ClientController@destroy');
});

Route::group(['prefix' => 'project'], function() {
    //Project Tasks
    Route::get('/{id}/tasks', 'ProjectController@getTasks');
    Route::post('/{id}/task', 'ProjectController@addTask');
    Route::get('/{id}/task/{task_id}', 'ProjectController@showTask');
    Route::delete('/{id}/task/{task_id}', 'ProjectController@removeTask');

    //Project Members
    Route::get('/{id}/members', 'ProjectController@getMembers');
    Route::post('/{id}/member/{user_id}', 'ProjectController@addMember');
    Route::get('/{id}/member/{user_id}', 'ProjectController@isMember');
    Route::delete('/{id}/member/{user_id}', 'ProjectController@removeMember');

    //Project
    Route::get('/', 'ProjectController@index');
    Route::post('/', 'ProjectController@store');
    Route::get('/{id}', 'ProjectController@show');
    Route::put('/{id}', 'ProjectController@update');
    Route::delete('/{id}', 'ProjectController@destroy');
});