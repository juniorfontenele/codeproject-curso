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

//OAuth2 Authentication
Route::post('/oauth/access_token', function(\LucaDegasperi\OAuth2Server\Authorizer $authorizer) {
    return Response::json($authorizer->issueAccessToken());
});

Route::group(['middleware' => 'oauth'], function() {
    Route::group(['prefix' => 'client'], function() {
        Route::get('/', 'ClientController@index');
        Route::post('/', 'ClientController@store');
        Route::get('/{id}', 'ClientController@show');
        Route::put('/{id}', 'ClientController@update');
        Route::delete('/{id}', 'ClientController@destroy');
    });

    Route::group(['prefix' => 'project'], function() {
        Route::group(['middleware' => 'CheckProjectPermission'], function() {
            //TODO: PROJECT NOTES

            //Project Tasks
            Route::get('/{id}/tasks', 'ProjectController@getTasks');
            Route::post('/{id}/task', 'ProjectController@addTask');
            Route::put('/{id}/task/{task_id}', 'ProjectController@updateTask');
            Route::get('/{id}/task/{task_id}', 'ProjectController@showTask');
            Route::delete('/{id}/task/{task_id}', 'ProjectController@removeTask');

            //Project Notes
            Route::get('/{id}/notes', 'ProjectController@getNotes');
            Route::post('/{id}/note', 'ProjectController@addNote');
            Route::put('/{id}/note/{note_id}', 'ProjectController@updateNote');
            Route::get('/{id}/note/{note_id}', 'ProjectController@showNote');
            Route::delete('/{id}/note/{note_id}', 'ProjectController@removeNote');

            //Project Members
            Route::get('/{id}/members', 'ProjectController@getMembers');
            Route::post('/{id}/member/{user_id}', ['middleware' => 'CheckProjectOwner', 'uses' => 'ProjectController@addMember']);
            Route::get('/{id}/member/{user_id}', 'ProjectController@isMember');
            Route::delete('/{id}/member/{user_id}', ['middleware' => 'CheckProjectOwner', 'uses' => 'ProjectController@removeMember']);
        });


        //Project
        Route::get('/', 'ProjectController@index');
        Route::post('/', 'ProjectController@store');
        Route::get('/{id}', ['uses' => 'ProjectController@show', 'middleware' => 'CheckProjectPermission']);
        Route::put('/{id}', ['uses' => 'ProjectController@update', 'middleware' => 'CheckProjectOwner']);
        Route::delete('/{id}', ['uses' => 'ProjectController@destroy', 'middleware' => 'CheckProjectOwner']);
    });
});